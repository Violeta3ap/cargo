<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda noslogojuma datu attēlošanu un sinhronizāciju.
class NoslogojumsController extends Controller
{
    private function getPeriodaKopsavilkums(string $periodaSakums, string $periodaBeigas)
    {
        return DB::table('vagonunoma')
            ->join('veidi', 'vagonunoma.VeidaID', '=', 'veidi.VeidaID')
            ->where('vagonunoma.NomasSakumaPeriods', '<=', $periodaBeigas)
            ->where('vagonunoma.NomasBeiguPeriods', '>=', $periodaSakums)
            ->select(
                'veidi.VeidaID',
                'veidi.Nosaukums as VeidaNosaukums',
                'veidi.VagonuSkaits as KopejaisVagonuSkaits',
                DB::raw('MAX(vagonunoma.VagonuSkaits) as MaksimaliNomatiVagoni')
            )
            ->groupBy('veidi.VeidaID', 'veidi.Nosaukums', 'veidi.VagonuSkaits')
            ->orderBy('veidi.Nosaukums')
            ->get();
    }

    // Sinhronizē noslogojuma tabulu ar nomas tabulas aktuālajiem datiem.
    private function syncNoslogojumsFromNoma(): void
    {
        $nomas = DB::table('vagonunoma')->get();
        $nomasIds = $nomas->pluck('NomasID')->unique()->values()->all();

        foreach ($nomas as $noma) {
            DB::table('noslogojums')->updateOrInsert(
                ['NomasID' => $noma->NomasID],
                [
                    'NomasSakumaPeriods' => $noma->NomasSakumaPeriods,
                    'NomasBeiguPeriods'  => $noma->NomasBeiguPeriods,
                    'VeidaID'            => $noma->VeidaID,
                ]
            );
        }

        if (empty($nomasIds)) {
            DB::table('noslogojums')->delete();
            return;
        }

        DB::table('noslogojums')->whereNotIn('NomasID', $nomasIds)->delete();
    }

    // Attēlo noslogojuma lapu ar datuma filtru.
    public function show(Request $request)
    {
        $this->syncNoslogojumsFromNoma();

        // Nolasa izvēlēto datumu vai izmanto šodienu.
        $datums = $request->query('datums', now()->format('Y-m-d'));

        // Validē datuma formātu
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datums)) {
            $datums = now()->format('Y-m-d');
        }

        // Kopsavilkums: grupēts pa vagona veidiem
        $kopsavilkums = DB::table('noslogojums')
            ->join('vagonunoma', 'noslogojums.NomasID', '=', 'vagonunoma.NomasID')
            ->join('veidi', 'noslogojums.VeidaID', '=', 'veidi.VeidaID')
            ->where('noslogojums.NomasSakumaPeriods', '<=', $datums)
            ->where('noslogojums.NomasBeiguPeriods', '>=', $datums)
            ->select(
                'veidi.VeidaID',
                'veidi.Nosaukums as VeidaNosaukums',
                'veidi.VagonuSkaits as KopejaisVagonuSkaits',
                DB::raw('SUM(vagonunoma.VagonuSkaits) as NomatiVagoni')
            )
            ->groupBy('veidi.VeidaID', 'veidi.Nosaukums', 'veidi.VagonuSkaits')
            ->orderBy('veidi.Nosaukums')
            ->get();

        // Detalizētais saraksts aktīvajām nomām.
        // Klients redz bez klienta datu kolonnām (view līmenī), admins redz pilnu versiju.
        $detali = DB::table('vagonunoma')
            ->leftJoin('klienti', 'vagonunoma.KlientaID', '=', 'klienti.KlientaID')
            ->leftJoin('krava', 'vagonunoma.KravasID', '=', 'krava.KravasID')
            ->leftJoin('veidi', 'vagonunoma.VeidaID', '=', 'veidi.VeidaID')
            ->where('vagonunoma.NomasSakumaPeriods', '<=', $datums)
            ->where('vagonunoma.NomasBeiguPeriods', '>=', $datums)
            ->select(
                'vagonunoma.NomasID',
                'vagonunoma.KlientaID',
                'vagonunoma.KravasID',
                'vagonunoma.VeidaID',
                'vagonunoma.VagonuSkaits',
                'vagonunoma.NomasSakumaPeriods',
                'vagonunoma.NomasBeiguPeriods',
                'vagonunoma.KopejaMaksa',
                'klienti.Vards as KlientaVards',
                'klienti.Uzvards as KlientaUzvards',
                'klienti.UznemumaNosaukums as KlientaUznemums',
                'krava.Nosaukums as KravasNosaukums',
                'veidi.Nosaukums as VeidaNosaukums'
            )
            ->orderBy('vagonunoma.NomasSakumaPeriods')
            ->orderBy('vagonunoma.NomasID')
            ->get();

        return view('Noslogojums', compact('kopsavilkums', 'detali', 'datums'));
    }

    // Attēlo perioda noslogojuma lapu ar datuma intervālu.
    public function showPeriod(Request $request)
    {
        $this->syncNoslogojumsFromNoma();

        $today = now()->format('Y-m-d');
        $periodaSakums = $request->query('perioda_sakums', $today);
        $periodaBeigas = $request->query('perioda_beigas', $today);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $periodaSakums)) {
            $periodaSakums = $today;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $periodaBeigas)) {
            $periodaBeigas = $today;
        }

        if ($periodaSakums > $periodaBeigas) {
            $tmp = $periodaSakums;
            $periodaSakums = $periodaBeigas;
            $periodaBeigas = $tmp;
        }

        $periodaKopsavilkums = $this->getPeriodaKopsavilkums($periodaSakums, $periodaBeigas);

        // Detalizētais saraksts ar nomas datiem perioda intervālā
        $detali = DB::table('vagonunoma')
            ->leftJoin('klienti', 'vagonunoma.KlientaID', '=', 'klienti.KlientaID')
            ->leftJoin('krava', 'vagonunoma.KravasID', '=', 'krava.KravasID')
            ->leftJoin('veidi', 'vagonunoma.VeidaID', '=', 'veidi.VeidaID')
            ->where('vagonunoma.NomasSakumaPeriods', '<=', $periodaBeigas)
            ->where('vagonunoma.NomasBeiguPeriods', '>=', $periodaSakums)
            ->select(
                'vagonunoma.NomasID',
                'vagonunoma.KlientaID',
                'vagonunoma.KravasID',
                'vagonunoma.VeidaID',
                'vagonunoma.VagonuSkaits',
                'vagonunoma.NomasSakumaPeriods',
                'vagonunoma.NomasBeiguPeriods',
                'vagonunoma.KopejaMaksa',
                'klienti.Vards as KlientaVards',
                'klienti.Uzvards as KlientaUzvards',
                'klienti.UznemumaNosaukums as KlientaUznemums',
                'krava.Nosaukums as KravasNosaukums',
                'veidi.Nosaukums as VeidaNosaukums'
            )
            ->orderBy('vagonunoma.NomasSakumaPeriods')
            ->orderBy('vagonunoma.NomasID')
            ->get();

        return view('NoslogojumsPeriods', compact('periodaSakums', 'periodaBeigas', 'periodaKopsavilkums', 'detali'));
    }
}
