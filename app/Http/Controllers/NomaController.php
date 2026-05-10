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

/**
 * NomaController - Pārvalda vagonu nomas sarakstu, pieejamību, aprēķinus un CRUD darbības
 * 
 * Šis kompleksais kontrolieris:
 * - Pārvaldīta vagonu nomu izveidi, rediģēšanu un dzēšanu
 * - Aprēķina nomas cenas un pieejamības
 * - Pārbauda vagonu availability konkrētiem periodiem
 * - Sinhronizē nomu statusus
 * - Atbalsta nomas arhivāciju un atjaunošanu
 */
class NomaController extends Controller
{
    /**
     * Pārbauda vai pašreizējais lietotājs ir administrators
     * 
     * @return bool - true, ja lietotājs ir administrators
     */
    private function userIsAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Pārbauda vai tabulā vagonunoma ir kolonna StatusaID
     * Šī kolonna satur nomas statusa ID (piemēram: "Apstiprināts", "Noraidīts" utt)
     * 
     * @return bool - true, ja kolonna eksistē
     */
    private function hasNomaStatusColumn(): bool
    {
        return Schema::hasColumn('vagonunoma', 'StatusaID');
    }

    /**
     * Pārbauda vai tabulā vagonunoma ir kolonna MaksasID
     * Šī kolonna satur maksas statusa ID
     * 
     * @return bool - true, ja kolonna eksistē
     */
    private function hasMaksasStatusColumn(): bool
    {
        return Schema::hasColumn('vagonunoma', 'MaksasID');
    }

    /**
     * Pārbauda vai eksistē arhīva tabula vagonunoma_arhivs
     * Šajā tabulā glabājas pabeigtas/anulētas nomas
     * 
     * @return bool - true, ja tabula eksistē
     */
    private function hasArchiveTable(): bool
    {
        return Schema::hasTable('vagonunoma_arhivs');
    }

    /**
     * Pārbauda vai tabulā vagonunoma ir kolonna AtteikumaIemesls
     * Šajā kolonnā glabājas iemesls, kāpēc noma tika noraidīta
     * 
     * @return bool - true, ja kolonna eksistē
     */
    private function hasAtteikumaIemeslsColumn(): bool
    {
        return Schema::hasColumn('vagonunoma', 'AtteikumaIemesls');
    }

    /**
     * Pārbauda vai arhīva tabulā ir kolonna AtteikumaIemesls
     * 
     * @return bool - true, ja kolonna eksistē
     */
    private function hasArchiveAtteikumaIemeslsColumn(): bool
    {
        return Schema::hasTable('vagonunoma_arhivs')
            && Schema::hasColumn('vagonunoma_arhivs', 'AtteikumaIemesls');
    }

    /**
     * Normalizē atteikuma iemesla vērtību - noņem liekās atstarpes
     * 
     * @param mixed $value - Ievades vērtība
     * @return string - Normalizēta vērtība
     */
    private function normalizeAtteikumaIemeslsValue($value): string
    {
        // Konvertē uz stringu un noņem liekās atstarpes
        return trim((string) ($value ?? ''));
    }

    /**
     * Meklē statusa ID pēc tā nosaukuma tabulā
     * Meklēšana ir case-insensitive (nemainīga burtu lielums)
     * 
     * @param string $table - Tabulas nosaukums
     * @param string $idColumn - ID kolonna nosaukums
     * @param string $name - Nosaukums, kuru meklēt
     * @return int|null - Statusa ID, vai null, ja nav atrasts
     */
    private function findStatusIdByName(string $table, string $idColumn, string $name): ?int
    {
        // Pārbauda vai tabula eksistē
        if (!Schema::hasTable($table)) {
            return null;
        }

        // Meklē ierakstu, kur Nosaukums atbilst (case-insensitive)
        $id = DB::table($table)
            ->whereRaw('LOWER(Nosaukums) = ?', [mb_strtolower($name)])
            ->value($idColumn);

        // Atgriež ID vai null
        return $id !== null ? (int) $id : null;
    }

    /**
     * Nodrošina, ka nomas statuss ar norādīto nosaukumu eksistē
     * Ja neeksistē, izveido jaunu
     * 
     * @param string $name - Statusa nosaukums (piemēram: "Apstiprināts")
     * @return int|null - Statusa ID
     */
    private function ensureNomasStatusId(string $name): ?int
    {
        // Pārbauda vai NomasStatuss tabula eksistē
        if (!Schema::hasTable('NomasStatuss')) {
            return null;
        }

        // Meklē esošu ID
        $existingId = $this->findStatusIdByName('NomasStatuss', 'StatusaID', $name);
        if ($existingId !== null) {
            return $existingId;
        }

        // Ja neeksistē, izveido jaunu ar nākamo ID
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
                try {
                    $noma->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    // Ja kļūda, turpina ar nākamo
                    continue;
                }
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
        $nomasStatuss = trim((string) $request->query('nomas_statuss', '')); // Jauns parametrs statusu filtrēšanai
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

        // Opcijas nomas statusa filtrēšanai
        $nomasStatusaOptions = collect();
        if (Schema::hasTable('NomasStatuss')) {
            $nomasStatusaOptions = NomasStatuss::orderBy('StatusaID')->pluck('Nosaukums')->unique()->values();
        }

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

        // Filtrēšana pēc nomas statusa
        if ($nomasStatuss !== '') {
            $query->whereHas('nomasStatuss', function ($q) use ($nomasStatuss) {
                $q->where('Nosaukums', $nomasStatuss);
            });
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

        if ($this->userIsAdmin() && $mekleKlientaUznemums !== '') {
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
            compact('noma', 'klientaVards', 'klientaUzvards', 'klientaUznemums', 'mekleKlientaUznemums', 'mekleKrava', 'mekleVeids', 'filtraUznemums', 'krava', 'veids', 'nomasStatuss', 'nomasSakumaPeriods', 'nomasBeiguPeriods', 'sortBy', 'sortOrder', 'kravaOptions', 'veidaOptions', 'nomasStatusaOptions')
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
                $archiveData['AtteikumaIemesls'] = $this->normalizeAtteikumaIemeslsValue($noma->AtteikumaIemesls);
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
            ->leftJoin('veidi as v', 'a.VeidaID', '=', 'v.Ve