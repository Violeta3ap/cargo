<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noma;
use Illuminate\Support\Facades\DB;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;
use Carbon\Carbon;

class NomaController extends Controller
{



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


    private function normalizeFilterDate(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

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

    private function validateVagonuSkaitsLimit(Request $dati): ?string
    {
        $veidaId = (int) $dati->input('VeidaID');
        $pieprasitsSkaits = (int) $dati->input('VagonuSkaits');

        $veids = Veidi::find($veidaId);

        if (!$veids) {
            return 'Izvēlētais vagona veids nav atrasts.';
        }

        if ($pieprasitsSkaits > (int) $veids->VagonuSkaits) {
            return 'Vagonu skaits nevar būt lielāks par izvēlētā veida pieejamo skaitu (' . $veids->VagonuSkaits . ').';
        }

        return null;
    }

    // Nomas saraksts ar pagināciju, meklēšanu, filtriem un kārtošanu.
    public function showAllNoma(Request $request)
    {
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

        if ($krava !== '') {
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
            ->paginate(5)
            ->appends($request->query());

        return view(
            'Noma',
            compact('noma', 'klientaVards', 'klientaUzvards', 'klientaUznemums', 'filtraUznemums', 'krava', 'veids', 'nomasSakumaPeriods', 'nomasBeiguPeriods', 'sortBy', 'sortOrder')
        );
    }

    // Dzēš nomas ierakstu.
    public function delete($id)
    {
        DB::table('vagonunoma')->where('NomasID', $id)->delete();
        return redirect('/Noma')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu ar saistītajiem sarakstiem.
    public function create()
    {
        $klienti = Klienti::all();
        $kravas = Kravas::all();
        $veidi = Veidi::all();

        return view('NomaPiev', compact('klienti','kravas','veidi'));
    }

    // Parāda viena ieraksta detaļas.
    public function details($id)
    {
        $noma = Noma::find($id);
        return view('NomaApskate', ['noma' => $noma]);
    }

    // Saglabā jaunu nomas ierakstu.
    public function NomaSubmit(Request $dati)
    {
        $dati->validate([
            'KlientaID' => ['required', 'integer'],
            'KravasID' => ['required', 'integer'],
            'VeidaID' => ['required', 'integer'],
            'VagonuSkaits' => ['required', 'integer', 'min:1'],
            'NomasSakumaPeriods' => ['required', 'date'],
            'NomasBeiguPeriods' => ['required', 'date'],
            'KopejaMaksa' => ['required', 'numeric', 'min:1'],
        ]);

        $vagonuSkaitsError = $this->validateVagonuSkaitsLimit($dati);
        if ($vagonuSkaitsError) {
            return back()->withInput()->withErrors(['VagonuSkaits' => $vagonuSkaitsError]);
        }

        $noma = new Noma();
        $noma->KlientaID = $dati->input('KlientaID');
        $noma->KravasID = $dati->input('KravasID');
        $noma->VeidaID = $dati->input('VeidaID');
        $noma->VagonuSkaits = $dati->input('VagonuSkaits');
        $noma->NomasSakumaPeriods = $dati->input('NomasSakumaPeriods');
        $noma->NomasBeiguPeriods = $dati->input('NomasBeiguPeriods');
        $noma->KopejaMaksa = $dati->input('KopejaMaksa');
        $noma->save();

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika pievienots');
    }

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        $noma = Noma::find($id);
        $klienti = Klienti::all();
        $kravas = Kravas::all();
        $veidi = Veidi::all();

        return view('NomaEdit', compact('noma','klienti','kravas','veidi'));
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        $dati->validate([
            'KlientaID' => ['required', 'integer'],
            'KravasID' => ['required', 'integer'],
            'VeidaID' => ['required', 'integer'],
            'VagonuSkaits' => ['required', 'integer', 'min:1'],
            'NomasSakumaPeriods' => ['required', 'date'],
            'NomasBeiguPeriods' => ['required', 'date'],
            'KopejaMaksa' => ['required', 'numeric', 'min:1'],
        ]);

        $vagonuSkaitsError = $this->validateVagonuSkaitsLimit($dati);
        if ($vagonuSkaitsError) {
            return back()->withInput()->withErrors(['VagonuSkaits' => $vagonuSkaitsError]);
        }

        DB::table('vagonunoma')
            ->where('NomasID', $id)
            ->update([
                'KlientaID' => $dati->input('KlientaID'),
                'KravasID' => $dati->input('KravasID'),
                'VeidaID' => $dati->input('VeidaID'),
                'VagonuSkaits' => $dati->input('VagonuSkaits'),
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
                'KopejaMaksa' => $dati->input('KopejaMaksa'),
            ]);

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika atjaunināts');
    }

    // **** JAUNĀS FUNKCIJAS ****
    
    // API: Iegūst vagona veidu pēc izvēlētās kravas
    public function getVeidsByKrava($kravasId)
    {
        $krava = Kravas::with('veidi')->find($kravasId);
        
        if ($krava && $krava->veidi) {
            return response()->json([
                'success' => true,
                'veida_id' => $krava->veidi->VeidaID,
                'veida_nosaukums' => $krava->veidi->Nosaukums,
                'cena_par_diennakti' => $krava->veidi->CenaParDiennakti
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
                $dienuSkaits = $start->diffInDays($end) + 1; // +1 lai ieskaitītu abus datumus
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