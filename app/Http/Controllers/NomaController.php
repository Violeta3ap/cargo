<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noma;
use Illuminate\Support\Facades\DB;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;
use Carbon\Carbon;

// Pārvalda nomas sarakstu, pieejamību, aprēķinus un CRUD darbības.
class NomaController extends Controller
{
    private function userIsAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }


    // Pārrēķina visu nomu kopējās maksas
    public function recalculateAll()
    {
        $nomas = Noma::with('veidi')->get();
        $updatedCount = 0;
        
        foreach ($nomas as $noma) {
            // Aprēķina dienu skaitu
            $start = Carbon::parse($noma->NomasSakumaPeriods);
            $end = Carbon::parse($noma->NomasBeiguPeriods);
            $dienuSkaits = $start->diffInDays($end) + 1;
            
            // Aprēķina kopējo maksu
            $cenaParDiennakti = $noma->veidi->CenaParDiennakti;
            $kopejaMaksa = $cenaParDiennakti * $noma->VagonuSkaits * $dienuSkaits;
            
            // Atjauno datubāzē
            if ($noma->KopejaMaksa != $kopejaMaksa) {
                $noma->KopejaMaksa = $kopejaMaksa;
                $noma->save();
                $updatedCount++;
            }
        }
        
        return redirect('/Noma')->with('success', "Pārrēķinātas $updatedCount nomas kopējās maksas.");
    }

    private function getDatePeriodOccupancy($veidaId, $sakumaDatums, $beiguDatums, $iznemtNomasID = null): array
    {
        // Iegūst izvēlēto vagona veidu un sākotnējo validāciju.
        $veids = Veidi::find($veidaId);
        if (!$veids || !$sakumaDatums || !$beiguDatums) {
            return ['kopejais' => 0, 'aiznemtais' => 0, 'pieejamais' => 0];
        }

        try {
            $periodaSakums = Carbon::parse($sakumaDatums)->startOfDay();
            $periodaBeigas = Carbon::parse($beiguDatums)->startOfDay();
        } catch (\Throwable $e) {
            return ['kopejais' => (int) $veids->VagonuSkaits, 'aiznemtais' => 0, 'pieejamais' => (int) $veids->VagonuSkaits];
        }

        if ($periodaBeigas->lt($periodaSakums)) {
            return ['kopejais' => (int) $veids->VagonuSkaits, 'aiznemtais' => 0, 'pieejamais' => (int) $veids->VagonuSkaits];
        }

        // Atrod nomas, kas pārklājas ar izvēlēto periodu.
        $query = Noma::where('VeidaID', $veidaId)
            ->where(function ($q) use ($periodaSakums, $periodaBeigas) {
                $q->where('NomasSakumaPeriods', '<=', $periodaBeigas->toDateString())
                  ->where('NomasBeiguPeriods', '>=', $periodaSakums->toDateString());
            });

        if ($iznemtNomasID) {
            $query->where('NomasID', '!=', $iznemtNomasID);
        }

        $nomas = $query->get(['NomasSakumaPeriods', 'NomasBeiguPeriods', 'VagonuSkaits']);
        $notikumi = [];

        // Veido notikumu sarakstu noslodzes aprēķinam pa datumiem.
        foreach ($nomas as $noma) {
            try {
                $nomaSakums = Carbon::parse($noma->NomasSakumaPeriods)->startOfDay();
                $nomaBeigas = Carbon::parse($noma->NomasBeiguPeriods)->startOfDay();
            } catch (\Throwable $e) {
                continue;
            }

            $sakums = $nomaSakums->greaterThan($periodaSakums) ? $nomaSakums->copy() : $periodaSakums->copy();
            $beigas = $nomaBeigas->lessThan($periodaBeigas) ? $nomaBeigas->copy() : $periodaBeigas->copy();

            if ($beigas->lt($sakums)) {
                continue;
            }

            $notikumi[$sakums->toDateString()] = ($notikumi[$sakums->toDateString()] ?? 0) + (int) $noma->VagonuSkaits;

            $pecBeigam = $beigas->copy()->addDay()->toDateString();
            $notikumi[$pecBeigam] = ($notikumi[$pecBeigam] ?? 0) - (int) $noma->VagonuSkaits;
        }

        ksort($notikumi);

        $maksimalaisAiznemtaisSkaits = 0;
        $pasreizejaisAiznemtaisSkaits = 0;

        // Atrod maksimālo vienlaicīgi aizņemto vagonu skaitu periodā.
        foreach ($notikumi as $izmainas) {
            $pasreizejaisAiznemtaisSkaits += $izmainas;
            $maksimalaisAiznemtaisSkaits = max($maksimalaisAiznemtaisSkaits, $pasreizejaisAiznemtaisSkaits);
        }

        $kopejaisVagonuSkaits = (int) $veids->VagonuSkaits;
        $pieejamaisSkaits = max(0, $kopejaisVagonuSkaits - $maksimalaisAiznemtaisSkaits);

        return [
            'kopejais' => $kopejaisVagonuSkaits,
            'aiznemtais' => $maksimalaisAiznemtaisSkaits,
            'pieejamais' => $pieejamaisSkaits,
        ];
    }

    // Pārbauda pieejamo vagonu skaitu izvēlētajā periodā
    private function getAvailableWagonsCount($veidaId, $sakumaDatums, $beiguDatums, $iznemtNomasID = null)
    {
        return $this->getDatePeriodOccupancy($veidaId, $sakumaDatums, $beiguDatums, $iznemtNomasID)['pieejamais'];
    }

    // API: Pārbauda pieejamo vagonu skaitu
    public function checkAvailability(Request $request)
    {
        $veidaId = $request->input('veida_id');
        $sakumaDatums = $request->input('sakuma_datums');
        $beiguDatums = $request->input('beigu_datums');
        $pieprasitaisSkaits = (int) $request->input('vagonu_skaits', 1);
        $nomasId = $request->input('nomas_id', null);
        
        $pieejamiba = $this->getDatePeriodOccupancy($veidaId, $sakumaDatums, $beiguDatums, $nomasId);
        
        return response()->json([
            'success' => true,
            'pieejamais_skaits' => $pieejamiba['pieejamais'],
            'pieprasitais_skaits' => $pieprasitaisSkaits,
            'ir_pieejams' => $pieprasitaisSkaits <= $pieejamiba['pieejamais'],
            'kopejais_skaits' => $pieejamiba['kopejais'],
            'aiznemtais_skaits' => $pieejamiba['aiznemtais']
        ]);
    }

    private function normalizeFilterDate(?string $value): ?string
    {
        // Notīra ievadi no liekajām atstarpēm.
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        // Atbalsta divus ievades formātus un pārvērš uz SQL formātu.
        foreach (['Y-m-d', 'd.m.Y'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                // Continue trying the next format.
            }
        }

        return null;
    }

    // Atjauninātā validateVagonuSkaitsLimit funkcija ar perioda pārbaudi
    private function validateVagonuSkaitsLimit(Request $dati, $nomasId = null)
    {
        $veidaId = (int) $dati->input('VeidaID');
        $pieprasitsSkaits = (int) $dati->input('VagonuSkaits');
        $sakumaDatums = $dati->input('NomasSakumaPeriods');
        $beiguDatums = $dati->input('NomasBeiguPeriods');

        $veids = Veidi::find($veidaId);

        if (!$veids) {
            return 'Izvēlētais vagona veids nav atrasts.';
        }

        // Pārbauda vai pieprasītais skaits nepārsniedz kopējo pieejamo skaitu
        if ($pieprasitsSkaits > (int) $veids->VagonuSkaits) {
            return 'Vagonu skaits nevar būt lielāks par izvēlētā veida kopējo skaitu (' . $veids->VagonuSkaits . ').';
        }
        
        // Pārbauda pieejamību izvēlētajā periodā
        $pieejamaisSkaits = $this->getAvailableWagonsCount($veidaId, $sakumaDatums, $beiguDatums, $nomasId);
        
        if ($pieprasitsSkaits > $pieejamaisSkaits) {
            return 'Izvēlētajā periodā nav pietiekami daudz brīvu vagonu. Pieejami: ' . $pieejamaisSkaits . ' no ' . $veids->VagonuSkaits . '.';
        }

        return null;
    }

    // Nomas saraksts ar pagināciju, meklēšanu, filtriem un kārtošanu.
    public function showAllNoma(Request $request)
    {
        // Nolasa filtrēšanas un meklēšanas parametrus no URL.
        $klientaVards = trim((string) $request->query('klienta_vards', ''));
        $klientaUzvards = trim((string) $request->query('klienta_uzvards', ''));
        $klientaUznemums = trim((string) $request->query('klienta_uznemums', ''));
        $filtraUznemums = trim((string) $request->query('filtra_uznemums', ''));
        $krava = trim((string) $request->query('krava', ''));
        $veids = trim((string) $request->query('veids', ''));
        $nomasSakumaPeriods = trim((string) $request->query('nomas_sakuma_periods', ''));
        $nomasBeiguPeriods = trim((string) $request->query('nomas_beigu_periods', ''));
        $nomasSakumaPeriodsSql = $this->normalizeFilterDate($nomasSakumaPeriods);
        $nomasBeiguPeriodsSql = $this->normalizeFilterDate($nomasBeiguPeriods);
        
        // Kārtošanas parametri
        $sortBy = $request->query('sort_by', 'NomasID');
        $sortOrder = $request->query('sort_order', 'asc');
        
        // Atļauto kārtošanas lauku saraksts (drošībai)
        $allowedSortFields = [
            'KlientaID',
            'KravasID',
            'VeidaID',
            'VagonuSkaits',
            'NomasSakumaPeriods',
            'NomasBeiguPeriods',
            'KopejaMaksa',
            'NomasID'
        ];
        
        // Pārbauda vai kārtošanas lauks ir atļauts
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'NomasID';
        }
        
        // Pārbauda kārtošanas virzienu
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query = Noma::query()
            ->with(['klienti', 'kravas', 'veidi']);

        // Tikai admins redz visas nomas un paplašinātos filtrus.
        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if ($klientsIeraksts) {
                $query->where('KlientaID', $klientsIeraksts->KlientaID);
            } else {
                $query->whereRaw('0=1');
            }
        } else {
            if ($klientaVards !== '' || $klientaUzvards !== '' || $klientaUznemums !== '') {
                $query->whereHas('klienti', function ($q) use ($klientaVards, $klientaUzvards, $klientaUznemums) {
                    if ($klientaVards !== '') {
                        $q->where('Vards', 'like', '%' . $klientaVards . '%');
                    }

                    if ($klientaUzvards !== '') {
                        $q->where('Uzvards', 'like', '%' . $klientaUzvards . '%');
                    }

                    if ($klientaUznemums !== '') {
                        $q->where('UznemumaNosaukums', 'like', '%' . $klientaUznemums . '%');
                    }
                });
            }
        }

        if ($krava !== '') {
            // Filtrs pēc kravas nosaukuma.
            $query->whereHas('kravas', function ($q) use ($krava) {
                $q->where('Nosaukums', 'like', '%' . $krava . '%');
            });
        }

        if ($veids !== '') {
            $query->whereHas('veidi', function ($q) use ($veids) {
                $q->where('Nosaukums', 'like', '%' . $veids . '%');
            });
        }

        if ($filtraUznemums !== '') {
            $query->whereHas('klienti', function ($q) use ($filtraUznemums) {
                $q->where('UznemumaNosaukums', 'like', '%' . $filtraUznemums . '%');
            });
        }

        if ($nomasSakumaPeriodsSql !== null) {
            $query->whereDate('NomasSakumaPeriods', '=', $nomasSakumaPeriodsSql);
        }

        if ($nomasBeiguPeriodsSql !== null) {
            $query->whereDate('NomasBeiguPeriods', '=', $nomasBeiguPeriodsSql);
        }

        $noma = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(15)
            ->appends($request->query());

        return view(
            'Noma',
            compact('noma', 'klientaVards', 'klientaUzvards', 'klientaUznemums', 'filtraUznemums', 'krava', 'veids', 'nomasSakumaPeriods', 'nomasBeiguPeriods', 'sortBy', 'sortOrder')
        );
    }

    // Dzēš nomas ierakstu.
    public function delete($id)
    {
        if (!$this->userIsAdmin()) {
            return redirect('/Noma')->with('error', 'Tikai administrators drīkst dzēst nomas ierakstus.');
        }

        DB::table('noslogojums')->where('NomasID', $id)->delete();
        DB::table('vagonunoma')->where('NomasID', $id)->delete();
        return redirect('/Noma')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu ar saistītajiem sarakstiem.
    public function create()
    {
        // Ne-adminam atļauj tikai viņa klienta ierakstu.
        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts) {
                return redirect('/Noma')->with('error', 'Jūsu kontam nav piesaistīts klienta ieraksts.');
            }

            $klienti = Klienti::where('KlientaID', $klientsIeraksts->KlientaID)->get();
        } else {
            $klienti = Klienti::all();
        }

        $kravas = Kravas::all();
        $veidi = Veidi::all();

        return view('NomaPiev', compact('klienti','kravas','veidi'));
    }

    // Parāda viena ieraksta detaļas.
    public function details($id)
    {
        $noma = Noma::find($id);

        if (!$noma) {
            return redirect('/Noma')->with('error', 'Ieraksts nav atrasts.');
        }

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts || $noma->KlientaID !== $klientsIeraksts->KlientaID) {
                return redirect('/Noma')->with('error', 'Jums nav tiesību skatīt šo nomas ierakstu.');
            }
        }

        return view('NomaApskate', ['noma' => $noma]);
    }

    // Saglabā jaunu nomas ierakstu.
    public function NomaSubmit(Request $dati)
    {
        // Validē obligātos laukus un to ierobežojumus.
        $dati->validate([
            'KlientaID' => ['required', 'integer'],
            'KravasID' => ['required', 'integer'],
            'VeidaID' => ['required', 'integer'],
            'VagonuSkaits' => ['required', 'integer', 'min:1'],
            'NomasSakumaPeriods' => ['required', 'date'],
            'NomasBeiguPeriods' => ['required', 'date', 'after_or_equal:NomasSakumaPeriods'],
            'KopejaMaksa' => ['required', 'numeric', 'min:1'],
        ]);

        $klientaId = (int) $dati->input('KlientaID');
        if (!$this->userIsAdmin()) {
            // Klientam vienmēr piesaista viņa paša KlientaID.
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts) {
                return back()->withInput()->withErrors(['KlientaID' => 'Jūsu kontam nav piesaistīts klienta ieraksts.']);
            }

            $klientaId = (int) $klientsIeraksts->KlientaID;
        }

        // Pārbauda vagonu skaitu (ieskaitot perioda pieejamību)
        $vagonuSkaitsError = $this->validateVagonuSkaitsLimit($dati);
        if ($vagonuSkaitsError) {
            return back()->withInput()->withErrors(['VagonuSkaits' => $vagonuSkaitsError]);
        }

        $noma = new Noma();
        $noma->KlientaID = $klientaId;
        $noma->KravasID = $dati->input('KravasID');
        $noma->VeidaID = $dati->input('VeidaID');
        $noma->VagonuSkaits = $dati->input('VagonuSkaits');
        $noma->NomasSakumaPeriods = $dati->input('NomasSakumaPeriods');
        $noma->NomasBeiguPeriods = $dati->input('NomasBeiguPeriods');
        $noma->KopejaMaksa = $dati->input('KopejaMaksa');
        $noma->save();

        // Sinhronizē noslogojums tabulu
        DB::table('noslogojums')->updateOrInsert(
            ['NomasID' => $noma->NomasID],
            [
                'NomasSakumaPeriods' => $noma->NomasSakumaPeriods,
                'NomasBeiguPeriods'  => $noma->NomasBeiguPeriods,
                'VeidaID'           => $noma->VeidaID,
            ]
        );

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika pievienots');
    }

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        $noma = Noma::find($id);

        if (!$noma) {
            return redirect('/Noma')->with('error', 'Ieraksts nav atrasts.');
        }

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts || $noma->KlientaID !== $klientsIeraksts->KlientaID) {
                return redirect('/Noma')->with('error', 'Jums nav tiesību rediģēt šo nomas ierakstu.');
            }

            $klienti = Klienti::where('KlientaID', $klientsIeraksts->KlientaID)->get();
        } else {
            $klienti = Klienti::all();
        }

        $kravas = Kravas::all();
        $veidi = Veidi::all();

        return view('NomaEdit', compact('noma','klienti','kravas','veidi'));
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        // Pārbauda vai rediģējamais ieraksts eksistē.
        $noma = Noma::find($id);
        if (!$noma) {
            return redirect('/Noma')->with('error', 'Ieraksts nav atrasts.');
        }

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts || $noma->KlientaID !== $klientsIeraksts->KlientaID) {
                return redirect('/Noma')->with('error', 'Jums nav tiesību rediģēt šo nomas ierakstu.');
            }
        }

        $dati->validate([
            'KlientaID' => ['required', 'integer'],
            'KravasID' => ['required', 'integer'],
            'VeidaID' => ['required', 'integer'],
            'VagonuSkaits' => ['required', 'integer', 'min:1'],
            'NomasSakumaPeriods' => ['required', 'date'],
            'NomasBeiguPeriods' => ['required', 'date', 'after_or_equal:NomasSakumaPeriods'],
            'KopejaMaksa' => ['required', 'numeric', 'min:1'],
        ]);

        $klientaId = (int) $dati->input('KlientaID');
        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts) {
                return back()->withInput()->withErrors(['KlientaID' => 'Jūsu kontam nav piesaistīts klienta ieraksts.']);
            }

            $klientaId = (int) $klientsIeraksts->KlientaID;
        }

        // Pārbauda vagonu skaitu (ieskaitot perioda pieejamību, izņemot pašreizējo ierakstu)
        $vagonuSkaitsError = $this->validateVagonuSkaitsLimit($dati, $id);
        if ($vagonuSkaitsError) {
            return back()->withInput()->withErrors(['VagonuSkaits' => $vagonuSkaitsError]);
        }

        DB::table('vagonunoma')
            ->where('NomasID', $id)
            ->update([
                'KlientaID' => $klientaId,
                'KravasID' => $dati->input('KravasID'),
                'VeidaID' => $dati->input('VeidaID'),
                'VagonuSkaits' => $dati->input('VagonuSkaits'),
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
                'KopejaMaksa' => $dati->input('KopejaMaksa'),
            ]);

        // Sinhronizē noslogojums tabulu
        DB::table('noslogojums')->updateOrInsert(
            ['NomasID' => $id],
            [
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods'  => $dati->input('NomasBeiguPeriods'),
                'VeidaID'           => $dati->input('VeidaID'),
            ]
        );

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika atjaunināts');
    }

    
    // API: Iegūst vagona veidu pēc izvēlētās kravas
    public function getVeidsByKrava($kravasId)
    {
        $krava = Kravas::with('veidi')->find($kravasId);
        
        if ($krava && $krava->veidi) {
            return response()->json([
                'success' => true,
                'veida_id' => $krava->veidi->VeidaID,
                'veida_nosaukums' => $krava->veidi->Nosaukums,
                'cena_par_diennakti' => $krava->veidi->CenaParDiennakti,
                'kopejais_vagonu_skaits' => $krava->veidi->VagonuSkaits
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Krava vai vagona veids nav atrasts'
        ]);
    }
    
    // API: Aprēķina kopējo maksu
    public function calculateTotal(Request $request)
    {
        $veidaId = $request->input('veida_id');
        $vagonuSkaits = $request->input('vagonu_skaits', 1);
        $sakumaDatums = $request->input('sakuma_datums');
        $beiguDatums = $request->input('beigu_datums');
        
        // Iegūst vagona veidu
        $veids = Veidi::find($veidaId);
        
        if (!$veids) {
            return response()->json([
                'success' => false,
                'message' => 'Vagona veids nav atrasts'
            ]);
        }
        
        // Aprēķina dienu skaitu
        $dienuSkaits = 0;
        if ($sakumaDatums && $beiguDatums) {
            try {
                $start = Carbon::parse($sakumaDatums);
                $end = Carbon::parse($beiguDatums);
                $dienuSkaits = $start->diffInDays($end) + 1;
                if ($dienuSkaits < 0) $dienuSkaits = 0;
            } catch (\Exception $e) {
                $dienuSkaits = 0;
            }
        }
        
        // Aprēķina kopējo maksu
        $cenaParDiennakti = $veids->CenaParDiennakti;
        $kopejaMaksa = $cenaParDiennakti * $vagonuSkaits * $dienuSkaits;
        
        return response()->json([
            'success' => true,
            'cena_par_diennakti' => $cenaParDiennakti,
            'dienu_skaits' => $dienuSkaits,
            'kopeja_maksa' => $kopejaMaksa,
            'formated_kopeja_maksa' => number_format($kopejaMaksa, 2)
        ]);
    }
}
