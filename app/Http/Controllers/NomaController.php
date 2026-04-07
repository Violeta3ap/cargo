<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;
use App\Models\NomasStatuss;
use App\Models\MaksasStatuss;
use Carbon\Carbon;

// Pārvalda nomas sarakstu, pieejamību, aprēķinus un CRUD darbības.
class NomaController extends Controller
{
    private function userIsAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    private function hasNomaStatusColumn(): bool
    {
        return Schema::hasColumn('vagonunoma', 'StatusaID');
    }

    private function hasMaksasStatusColumn(): bool
    {
        return Schema::hasColumn('vagonunoma', 'MaksasID');
    }

    private function hasArchiveTable(): bool
    {
        return Schema::hasTable('vagonunoma_arhivs');
    }

    private function hasAtteikumaIemeslsColumn(): bool
    {
        return Schema::hasColumn('vagonunoma', 'AtteikumaIemesls');
    }

    private function hasArchiveAtteikumaIemeslsColumn(): bool
    {
        return Schema::hasTable('vagonunoma_arhivs')
            && Schema::hasColumn('vagonunoma_arhivs', 'AtteikumaIemesls');
    }

    private function findStatusIdByName(string $table, string $idColumn, string $name): ?int
    {
        if (!Schema::hasTable($table)) {
            return null;
        }

        $id = DB::table($table)
            ->whereRaw('LOWER(Nosaukums) = ?', [mb_strtolower($name)])
            ->value($idColumn);

        return $id !== null ? (int) $id : null;
    }

    private function ensureNomasStatusId(string $name): ?int
    {
        if (!Schema::hasTable('NomasStatuss')) {
            return null;
        }

        $existingId = $this->findStatusIdByName('NomasStatuss', 'StatusaID', $name);
        if ($existingId !== null) {
            return $existingId;
        }

        $nextId = (int) DB::table('NomasStatuss')->max('StatusaID') + 1;
        DB::table('NomasStatuss')->insert([
            'StatusaID' => $nextId,
            'Nosaukums' => $name,
        ]);

        return $nextId;
    }

    private function resolveDefaultMaksasStatusId(): int
    {
        if (!Schema::hasTable('MaksasStatuss')) {
            return 1;
        }

        $navApmaksatsId = $this->findStatusIdByName('MaksasStatuss', 'MaksasID', 'Nav apmaksāts');
        if ($navApmaksatsId !== null) {
            return $navApmaksatsId;
        }

        $firstId = DB::table('MaksasStatuss')->orderBy('MaksasID')->value('MaksasID');

        return $firstId !== null ? (int) $firstId : 1;
    }

    private function applyCompletionStatus($nomas)
    {
        $today = Carbon::today();
        $statusiBezPabeigsanas = ['noraidīts', 'nepieteikts'];

        foreach ($nomas as $noma) {
            $statusaNosaukums = trim((string) optional($noma->nomasStatuss)->Nosaukums);
            if (in_array(mb_strtolower($statusaNosaukums), $statusiBezPabeigsanas, true)) {
                $noma->PabeigsanasStatuss = null;
                continue;
            }

            try {
                $beiguDatums = Carbon::parse($noma->NomasBeiguPeriods)->startOfDay();
                $noma->PabeigsanasStatuss = $beiguDatums->lt($today) ? 'Pabeigts' : 'Nav pabeigts';
            } catch (\Throwable $e) {
                $noma->PabeigsanasStatuss = null;
            }
        }
    }

    private function isNomaCompleted(Noma $noma): bool
    {
        $statusaNosaukums = trim((string) optional($noma->nomasStatuss)->Nosaukums);
        if (in_array(mb_strtolower($statusaNosaukums), ['noraidīts', 'nepieteikts'], true)) {
            return false;
        }

        try {
            return Carbon::parse($noma->NomasBeiguPeriods)->startOfDay()->lt(Carbon::today());
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function calculateCompletionStatusFromValues(?string $statusaNosaukums, ?string $beiguPeriods): ?string
    {
        if (in_array(mb_strtolower(trim((string) $statusaNosaukums)), ['noraidīts', 'nepieteikts'], true)) {
            return null;
        }

        try {
            return Carbon::parse($beiguPeriods)->startOfDay()->lt(Carbon::today()) ? 'Pabeigts' : 'Nav pabeigts';
        } catch (\Throwable $e) {
            return null;
        }
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
        $mekleKlientaUznemums = trim((string) $request->query('mekle_klienta_uznemums', ''));
        $mekleKrava = trim((string) $request->query('mekle_krava', ''));
        $mekleVeids = trim((string) $request->query('mekle_veids', ''));
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
            'NomasStatuss',
            'MaksasStatuss',
            'PabeigsanasStatuss',
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

        $kravaOptions = Kravas::query()
            ->orderBy('Nosaukums')
            ->pluck('Nosaukums')
            ->unique()
            ->values();

        $veidaOptions = Veidi::query()
            ->orderBy('Nosaukums')
            ->pluck('Nosaukums')
            ->unique()
            ->values();

        $query = Noma::query()
            ->select('vagonunoma.*')
            ->with(['klienti', 'kravas', 'veidi', 'nomasStatuss', 'maksasStatuss']);

        $hasNomasStatusJoin = false;
        if (Schema::hasTable('NomasStatuss') && $this->hasNomaStatusColumn()) {
            $query->leftJoin('NomasStatuss as nomas_statuss', 'vagonunoma.StatusaID', '=', 'nomas_statuss.StatusaID');
            $hasNomasStatusJoin = true;
        }

        $hasMaksasStatusJoin = false;
        if (Schema::hasTable('MaksasStatuss') && $this->hasMaksasStatusColumn()) {
            $query->leftJoin('MaksasStatuss as maksas_statuss', 'vagonunoma.MaksasID', '=', 'maksas_statuss.MaksasID');
            $hasMaksasStatusJoin = true;
        }

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
            // Filtrs pēc kravas nosaukuma no izkrītošā saraksta.
            $query->whereHas('kravas', function ($q) use ($krava) {
                $q->where('Nosaukums', $krava);
            });
        }

        if ($veids !== '') {
            $query->whereHas('veidi', function ($q) use ($veids) {
                $q->where('Nosaukums', $veids);
            });
        }

        if ($filtraUznemums !== '') {
            $query->whereHas('klienti', function ($q) use ($filtraUznemums) {
                $q->where('UznemumaNosaukums', 'like', '%' . $filtraUznemums . '%');
            });
        }

        if ($mekleKlientaUznemums !== '') {
            $query->whereHas('klienti', function ($q) use ($mekleKlientaUznemums) {
                $q->where('UznemumaNosaukums', 'like', '%' . $mekleKlientaUznemums . '%');
            });
        }

        if ($mekleKrava !== '') {
            $query->whereHas('kravas', function ($q) use ($mekleKrava) {
                $q->where('Nosaukums', 'like', '%' . $mekleKrava . '%');
            });
        }

        if ($mekleVeids !== '') {
            $query->whereHas('veidi', function ($q) use ($mekleVeids) {
                $q->where('Nosaukums', 'like', '%' . $mekleVeids . '%');
            });
        }

        if ($nomasSakumaPeriodsSql !== null) {
            $query->whereDate('NomasSakumaPeriods', '=', $nomasSakumaPeriodsSql);
        }

        if ($nomasBeiguPeriodsSql !== null) {
            $query->whereDate('NomasBeiguPeriods', '=', $nomasBeiguPeriodsSql);
        }

        if ($sortBy === 'NomasStatuss' && $hasNomasStatusJoin) {
            $query->orderByRaw("COALESCE(nomas_statuss.Nosaukums, '') {$sortOrder}");
        } elseif ($sortBy === 'MaksasStatuss' && $hasMaksasStatusJoin) {
            $query->orderByRaw("COALESCE(maksas_statuss.Nosaukums, '') {$sortOrder}");
        } elseif ($sortBy === 'PabeigsanasStatuss') {
            $query->orderByRaw(
                "(CASE WHEN vagonunoma.NomasBeiguPeriods < ? THEN 'Pabeigts' ELSE 'Nav pabeigts' END) {$sortOrder}",
                [Carbon::today()->toDateString()]
            );
        } else {
            $query->orderBy('vagonunoma.' . $sortBy, $sortOrder);
        }

        $noma = $query
            ->paginate(15)
            ->appends($request->query());

        $this->applyCompletionStatus($noma->getCollection());

        return view(
            'Noma',
            compact('noma', 'klientaVards', 'klientaUzvards', 'klientaUznemums', 'mekleKlientaUznemums', 'mekleKrava', 'mekleVeids', 'filtraUznemums', 'krava', 'veids', 'nomasSakumaPeriods', 'nomasBeiguPeriods', 'sortBy', 'sortOrder', 'kravaOptions', 'veidaOptions')
        );
    }

    // Dzēš nomas ierakstu.
    public function delete($id)
    {
        if (!$this->hasArchiveTable()) {
            return redirect('/Noma')->with('error', 'Arhīva tabula nav atrasta. Izveidojiet vagonunoma_arhivs tabulu.');
        }

        $noma = Noma::with('nomasStatuss')->find($id);
        if (!$noma) {
            return redirect('/Noma')->with('error', 'Ieraksts nav atrasts.');
        }

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts || $noma->KlientaID !== $klientsIeraksts->KlientaID) {
                return redirect('/Noma')->with('error', 'Jums nav tiesību dzēst šo nomas ierakstu.');
            }
        }

        if ($this->isNomaCompleted($noma)) {
            return redirect('/Noma')->with('error', 'Pabeigtu nomu dzēst nevar.');
        }

        DB::transaction(function () use ($noma, $id) {
            $archiveData = [
                'NomasID' => $noma->NomasID,
                'KlientaID' => $noma->KlientaID,
                'KravasID' => $noma->KravasID,
                'VeidaID' => $noma->VeidaID,
                'VagonuSkaits' => $noma->VagonuSkaits,
                'NomasSakumaPeriods' => $noma->NomasSakumaPeriods,
                'NomasBeiguPeriods' => $noma->NomasBeiguPeriods,
                'StatusaID' => $this->hasNomaStatusColumn() ? $noma->StatusaID : null,
                'KopejaMaksa' => $noma->KopejaMaksa,
                'MaksasID' => $this->hasMaksasStatusColumn() ? $noma->MaksasID : null,
            ];

            if ($this->hasAtteikumaIemeslsColumn() && $this->hasArchiveAtteikumaIemeslsColumn()) {
                $archiveData['AtteikumaIemesls'] = $noma->AtteikumaIemesls;
            }

            DB::table('vagonunoma_arhivs')->insert($archiveData);

            DB::table('noslogojums')->where('NomasID', $id)->delete();
            DB::table('vagonunoma')->where('NomasID', $id)->delete();
        });

        return redirect('/Noma')->with('success', 'Ieraksts tika arhivēts.');
    }

    public function showArchive(Request $request)
    {
        if (!$this->hasArchiveTable()) {
            return redirect('/Noma')->with('error', 'Arhīva tabula nav atrasta. Izveidojiet vagonunoma_arhivs tabulu.');
        }

        $query = DB::table('vagonunoma_arhivs as a')
            ->leftJoin('klienti as k', 'a.KlientaID', '=', 'k.KlientaID')
            ->leftJoin('krava as kr', 'a.KravasID', '=', 'kr.KravasID')
            ->leftJoin('veidi as v', 'a.VeidaID', '=', 'v.VeidaID')
            ->leftJoin('NomasStatuss as ns', 'a.StatusaID', '=', 'ns.StatusaID')
            ->leftJoin('MaksasStatuss as ms', 'a.MaksasID', '=', 'ms.MaksasID')
            ->select([
                'a.*',
                'k.Vards as KlientaVards',
                'k.Uzvards as KlientaUzvards',
                'k.UznemumaNosaukums as KlientaUznemums',
                'kr.Nosaukums as KravasNosaukums',
                'v.Nosaukums as VeidaNosaukums',
                'ns.Nosaukums as NomasStatusaNosaukums',
                'ms.Nosaukums as MaksasStatusaNosaukums',
            ]);

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts) {
                $query->whereRaw('0=1');
            } else {
                $query->where('a.KlientaID', $klientsIeraksts->KlientaID);
            }
        }

        $arhivs = $query
            ->orderByDesc('a.NomasID')
            ->paginate(15)
            ->appends($request->query());

        foreach ($arhivs as $item) {
            $item->PabeigsanasStatuss = $this->calculateCompletionStatusFromValues($item->NomasStatusaNosaukums, $item->NomasBeiguPeriods);
        }

        return view('NomaArhivs', compact('arhivs'));
    }

    public function restoreFromArchive($id)
    {
        if (!$this->hasArchiveTable()) {
            return redirect('/Noma')->with('error', 'Arhīva tabula nav atrasta. Izveidojiet vagonunoma_arhivs tabulu.');
        }

        $arhivaIeraksts = DB::table('vagonunoma_arhivs')->where('NomasID', $id)->first();
        if (!$arhivaIeraksts) {
            return redirect('/Noma/arhivs')->with('error', 'Arhīva ieraksts nav atrasts.');
        }

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts || (int) $arhivaIeraksts->KlientaID !== (int) $klientsIeraksts->KlientaID) {
                return redirect('/Noma/arhivs')->with('error', 'Jums nav tiesību atjaunot šo arhīva ierakstu.');
            }
        }

        if (DB::table('vagonunoma')->where('NomasID', $id)->exists()) {
            return redirect('/Noma/arhivs')->with('error', 'Atjaunošana nav iespējama: aktīvajā tabulā jau eksistē šāds NomasID.');
        }

        DB::transaction(function () use ($arhivaIeraksts) {
            $insertData = [
                'NomasID' => $arhivaIeraksts->NomasID,
                'KlientaID' => $arhivaIeraksts->KlientaID,
                'KravasID' => $arhivaIeraksts->KravasID,
                'VeidaID' => $arhivaIeraksts->VeidaID,
                'VagonuSkaits' => $arhivaIeraksts->VagonuSkaits,
                'NomasSakumaPeriods' => $arhivaIeraksts->NomasSakumaPeriods,
                'NomasBeiguPeriods' => $arhivaIeraksts->NomasBeiguPeriods,
                'KopejaMaksa' => $arhivaIeraksts->KopejaMaksa,
            ];

            if (Schema::hasColumn('vagonunoma', 'Svars')) {
                $insertData['Svars'] = 0;
            }

            if ($this->hasNomaStatusColumn()) {
                $insertData['StatusaID'] = $arhivaIeraksts->StatusaID;
            }

            if ($this->hasMaksasStatusColumn()) {
                $insertData['MaksasID'] = $arhivaIeraksts->MaksasID !== null
                    ? (int) $arhivaIeraksts->MaksasID
                    : $this->resolveDefaultMaksasStatusId();
            }

            if ($this->hasAtteikumaIemeslsColumn() && isset($arhivaIeraksts->AtteikumaIemesls)) {
                $insertData['AtteikumaIemesls'] = $arhivaIeraksts->AtteikumaIemesls;
            }

            DB::table('vagonunoma')->insert($insertData);

            DB::table('noslogojums')->updateOrInsert(
                ['NomasID' => $arhivaIeraksts->NomasID],
                [
                    'NomasSakumaPeriods' => $arhivaIeraksts->NomasSakumaPeriods,
                    'NomasBeiguPeriods'  => $arhivaIeraksts->NomasBeiguPeriods,
                    'VeidaID' => $arhivaIeraksts->VeidaID,
                ]
            );

            DB::table('vagonunoma_arhivs')->where('NomasID', $arhivaIeraksts->NomasID)->delete();
        });

        return redirect('/Noma/arhivs')->with('success', 'Ieraksts tika atjaunots no arhīva.');
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

        $this->applyCompletionStatus([$noma]);

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

        if ($this->hasNomaStatusColumn()) {
            $noma->StatusaID = $this->ensureNomasStatusId('Pieteikts');
        }

        if ($this->hasMaksasStatusColumn()) {
            $noma->MaksasID = $this->resolveDefaultMaksasStatusId();
        }

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
        $noma = Noma::with('nomasStatuss')->find($id);

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

        if ($this->isNomaCompleted($noma)) {
            return redirect('/Noma')->with('error', 'Pabeigtu nomu rediģēt nevar.');
        }

        $kravas = Kravas::all();
        $veidi = Veidi::all();

        $nomasStatusi = collect();
        $maksasStatusi = collect();

        if ($this->userIsAdmin() && Schema::hasTable('NomasStatuss')) {
            $nomasStatusi = NomasStatuss::whereRaw('LOWER(Nosaukums) != ?', [mb_strtolower('Pieņemts')])
                ->orderBy('StatusaID')
                ->get();
        }

        if ($this->userIsAdmin() && Schema::hasTable('MaksasStatuss')) {
            $maksasStatusi = MaksasStatuss::orderBy('MaksasID')->get();
        }

        return view('NomaEdit', compact('noma','klienti','kravas','veidi', 'nomasStatusi', 'maksasStatusi'));
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        // Pārbauda vai rediģējamais ieraksts eksistē.
        $noma = Noma::with('nomasStatuss')->find($id);
        if (!$noma) {
            return redirect('/Noma')->with('error', 'Ieraksts nav atrasts.');
        }

        if (!$this->userIsAdmin()) {
            $klientsIeraksts = auth()->user()->klienti;
            if (!$klientsIeraksts || $noma->KlientaID !== $klientsIeraksts->KlientaID) {
                return redirect('/Noma')->with('error', 'Jums nav tiesību rediģēt šo nomas ierakstu.');
            }
        }

        if ($this->isNomaCompleted($noma)) {
            return redirect('/Noma')->with('error', 'Pabeigtu nomu rediģēt nevar.');
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

        if ($this->userIsAdmin() && $this->hasNomaStatusColumn()) {
            $dati->validate([
                'StatusaID' => ['nullable', 'integer'],
            ]);
        }

        if ($this->userIsAdmin() && $this->hasAtteikumaIemeslsColumn()) {
            $dati->validate([
                'AtteikumaIemesls' => ['nullable', 'string', 'max:2000'],
            ]);
        }

        if ($this->userIsAdmin() && $this->hasMaksasStatusColumn()) {
            $dati->validate([
                'MaksasID' => ['nullable', 'integer'],
            ]);
        }

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

        $updateData = [
            'KlientaID' => $klientaId,
            'KravasID' => $dati->input('KravasID'),
            'VeidaID' => $dati->input('VeidaID'),
            'VagonuSkaits' => $dati->input('VagonuSkaits'),
            'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
            'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
            'KopejaMaksa' => $dati->input('KopejaMaksa'),
        ];

        if ($this->userIsAdmin() && $this->hasNomaStatusColumn()) {
            $statusaId = $dati->input('StatusaID');

            if ($statusaId !== null && $statusaId !== '') {
                $pienemtsId = $this->findStatusIdByName('NomasStatuss', 'StatusaID', 'Pieņemts');
                if ($pienemtsId !== null && (int) $statusaId === (int) $pienemtsId) {
                    return back()->withInput()->withErrors(['StatusaID' => 'Statuss Pieņemts vairs nav atļauts.']);
                }
            }

            $updateData['StatusaID'] = $statusaId !== null && $statusaId !== '' ? (int) $statusaId : null;

            if ($this->hasAtteikumaIemeslsColumn()) {
                $noraiditsId = $this->findStatusIdByName('NomasStatuss', 'StatusaID', 'Noraidīts');
                $irNoraidits = $noraiditsId !== null
                    && $updateData['StatusaID'] !== null
                    && (int) $updateData['StatusaID'] === (int) $noraiditsId;

                $atteikumaIemesls = trim((string) $dati->input('AtteikumaIemesls', ''));

                if ($irNoraidits && $atteikumaIemesls === '') {
                    return back()->withInput()->withErrors([
                        'AtteikumaIemesls' => 'Ja statuss ir Noraidīts, laukam "Atteikuma iemesls" ir jābūt aizpildītam.'
                    ]);
                }

                $updateData['AtteikumaIemesls'] = $irNoraidits ? $atteikumaIemesls : null;
            }
        }

        if ($this->userIsAdmin() && $this->hasMaksasStatusColumn()) {
            $maksasId = $dati->input('MaksasID');

            if (($maksasId === null || $maksasId === '') && isset($updateData['StatusaID'])) {
                $pieteiktsId = $this->findStatusIdByName('NomasStatuss', 'StatusaID', 'Pieteikts');
                if ($pieteiktsId === null || (int) $updateData['StatusaID'] !== $pieteiktsId) {
                    $maksasId = $this->findStatusIdByName('MaksasStatuss', 'MaksasID', 'Nav apmaksāts');
                }
            }

            if ($maksasId === null || $maksasId === '') {
                $maksasId = $this->resolveDefaultMaksasStatusId();
            }

            $updateData['MaksasID'] = (int) $maksasId;
        }

        if ($this->userIsAdmin() && $this->hasNomaStatusColumn() && $this->hasMaksasStatusColumn()) {
            $pieteiktsId = $this->findStatusIdByName('NomasStatuss', 'StatusaID', 'Pieteikts');
            $navApmaksatsId = $this->findStatusIdByName('MaksasStatuss', 'MaksasID', 'Nav apmaksāts');

            if (
                $pieteiktsId !== null
                && $navApmaksatsId !== null
                && isset($updateData['StatusaID'], $updateData['MaksasID'])
                && (int) $updateData['StatusaID'] === (int) $pieteiktsId
                && (int) $updateData['MaksasID'] === (int) $navApmaksatsId
            ) {
                return back()->withInput()->withErrors([
                    'StatusaID' => 'Statusu "Pieteikts" nevar izvēlēties, ja maksas statuss ir "Nav apmaksāts".'
                ]);
            }
        }

        DB::table('vagonunoma')
            ->where('NomasID', $id)
            ->update($updateData);

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
