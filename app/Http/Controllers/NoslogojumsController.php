<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * NoslogojumsController - Pārvalda noslogojuma datu attēlošanu un sinhronizāciju
 * 
 * Noslogojums parāda, cik vagoni ir aizņemti/noslodzīti konkrētā datumā vai perioda laikā
 * Kontrolieris:
 * - Attēlo vagonu slodzi pa datumiem
 * - Sinhronizē noslogojuma tabulu ar nomas tabulā esošiem aktuālajiem datiem
 * - Nodrošina detalizētu un kopsavilkuma vēlējuma
 */
class NoslogojumsController extends Controller
{
    /**
     * Iegūst perioda noslogojuma kopsavilkumu
     * Parāda, cik maksimāli vagoni bija noslodzīti katrā vagona veidā periodā
     * 
     * @param string $periodaSakums - Perioda sākuma datums (Y-m-d formātā)
     * @param string $periodaBeigas - Perioda beigu datums (Y-m-d formātā)
     * @return Illuminate\Support\Collection - Vagoņos uz vietu dati pa veidiem
     */
    private function getPeriodaKopsavilkums(string $periodaSakums, string $periodaBeigas)
    {
        // Izveidojam vaicājumu, kas apkopo datus par vagonu slodzi periodā
        return DB::table('vagonunoma')
            // Pievienojam informāciju par vagona veidiem
            ->join('veidi', 'vagonunoma.VeidaID', '=', 'veidi.VeidaID')
            // Filtrējam nomas, kas pārklājas ar mūsu periodu
            ->where('vagonunoma.NomasSakumaPeriods', '<=', $periodaBeigas)
            ->where('vagonunoma.NomasBeiguPeriods', '>=', $periodaSakums)
            // Izvēlamies kolonna
            ->select(
                'veidi.VeidaID',
                'veidi.Nosaukums as VeidaNosaukums',
                'veidi.VagonuSkaits as KopejaisVagonuSkaits',
                // Aprēķinām maksimālo nomāto vagonu skaitu šajā veidā
                DB::raw('MAX(vagonunoma.VagonuSkaits) as MaksimaliNomatiVagoni')
            )
            // Grupējam pēc vagona veida (kopsavilkums)
            ->groupBy('veidi.VeidaID', 'veidi.Nosaukums', 'veidi.VagonuSkaits')
            ->orderBy('veidi.Nosaukums')
            ->get();
    }

    /**
     * Sinhronizē noslogojuma tabulu ar nomas tabulas aktuālajiem datiem
     * Šī funkcija tiek izsaukta katru reizi, kad tiek skatīta noslodze
     * Nodrošina, ka noslogojuma tabula vienmēr ir aktuāla
     * 
     * @return void
     */
    private function syncNoslogojumsFromNoma(): void
    {
        // Iegūstam visas nomas no vagonunoma tabulas
        $nomas = DB::table('vagonunoma')->get();
        // Iegūstam unikālos nomas ID
        $nomasIds = $nomas->pluck('NomasID')->unique()->values()->all();

        // Katrai nomai pievienojam vai atjauninājam ierakstu noslogojuma tabulā
        foreach ($nomas as $noma) {
            DB::table('noslogojums')->updateOrInsert(
                ['NomasID' => $noma->NomasID], // Atrod vai izveido pēc NomasID
                [
                    'NomasSakumaPeriods' => $noma->NomasSakumaPeriods,
                    'NomasBeiguPeriods'  => $noma->NomasBeiguPeriods,
                    'VeidaID'            => $noma->VeidaID,
                ]
            );
        }

        // Ja nav nomas, iztīrām noslogojuma tabulu
        if (empty($nomasIds)) {
            DB::table('noslogojums')->delete();
            return;
        }

        // Dzēšam ierakstus noslogojuma tabulā, kuriem nav atbilstošas nomas
        DB::table('noslogojums')->whereNotIn('NomasID', $nomasIds)->delete();
    }

    /**
     * Attēlo noslogojuma lapu ar datuma filtru (viena diena)
     * Parāda vagonu slodzi konkrētajā datumā
     * 
     * @param Illuminate\Http\Request $request - Pieprasījums ar datuma parametru
     * @return Illuminate\View\View
     */
    public function show(Request $request)
    {
        // Sinhronizējam noslogojuma tabulu ar jaunākajiem datos
        $this->syncNoslogojumsFromNoma();

        // Nolasam izvēlēto datumu no URL, ja nav, izmantojam šodienu
        $datums = $request->query('datums', now()->format('Y-m-d'));

        // Validējam datuma formātu (Y-m-d)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datums)) {
            $datums = now()->format('Y-m-d');
        }

        // Kopsavilkums: grupēts pa vagona veidiem konkrētajam datumam
        $kopsavilkums = DB::table('noslogojums')
            ->join('vagonunoma', 'noslogojums.NomasID', '=', 'vagonunoma.NomasID')
            ->join('veidi', 'noslogojums.VeidaID', '=', 'veidi.VeidaID')
            // Filtrējam nomas, kas ir aktīvas dotajā datumā
            ->where('noslogojums.NomasSakumaPeriods', '<=', $datums)
            ->where('noslogojums.NomasBeiguPeriods', '>=', $datums)
            ->select(
                'veidi.VeidaID',
                'veidi.Nosaukums as VeidaNosaukums',
                'veidi.VagonuSkaits as KopejaisVagonuSkaits',
                // Sumējam nomātos vagonu skaitus pa veidiem
                DB::raw('SUM(vagonunoma.VagonuSkaits) as NomatiVagoni')
            )
            ->groupBy('veidi.VeidaID', 'veidi.Nosaukums', 'veidi.VagonuSkaits')
            ->orderBy('veidi.Nosaukums')
            ->get();

        // Detalizētais saraksts ar aktīvajām nomām konkrētajam datumam
        // Klients redz skatu bez klienta datu kolonnām, admins redz pilnu versiju
        $detali = DB::table('vagonunoma')
            // Pievienojam klienta datus
            ->leftJoin('klienti', 'vagonunoma.KlientaID', '=', 'klienti.KlientaID')
            // Pievienojam kravas datus
            ->leftJoin('krava', 'vagonunoma.KravasID', '=', 'krava.KravasID')
            // Pievienojam vagona veida datus
            ->leftJoin('veidi', 'vagonunoma.VeidaID', '=', 'veidi.VeidaID')
            // Filtrējam nomas, kas ir aktīvas dotajā datumā
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

        // Atgriežam skatu ar datiem
        return view('Noslogojums', compact('kopsavilkums', 'detali', 'datums'));
    }

    /**
     * Attēlo perioda noslogojuma lapu ar datuma intervālu
     * Parāda vagonu slodzi visā norādītajā periodā
     * 
     * @param Illuminate\Http\Request $request - Pieprasījums ar perioda parametriem
     * @return Illuminate\View\View
     */
    public function showPeriod(Request $request)
    {
        // Sinhronizējam noslogojuma tabulu
        $this->syncNoslogojumsFromNoma();

        // Šodienas datums noklusējumā
        $today = now()->format('Y-m-d');
        // Nolasām perioda sākumu un beigas no URL
        $periodaSakums = $request->query('perioda_sakums', $today);
        $periodaBeigas = $request->query('perioda_beigas', $today);

        // Validējam perioda sākuma datuma formātu
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $periodaSakums)) {
            $periodaSakums = $today;
        }

        // Validējam perioda beigu datuma formātu
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $periodaBeigas)) {
            $periodaBeigas = $today;
        }

        // Ja perioda sākums ir pēc beigu, mainām vietām
        if ($periodaSakums > $periodaBeigas) {
            $tmp = $periodaSakums;
            $periodaSakums = $periodaBeigas;
            $periodaBeigas = $tmp;
        }

        // Iegūstam perioda kopsavilkumu
        $periodaKopsavilkums = $this->getPeriodaKopsavilkums($periodaSakums, $periodaBeigas);

        // Detalizētais saraksts ar nomām periodā
        $detali = DB::table('vagonunoma')
            // Pievienojam klienta datus
            ->leftJoin('klienti', 'vagonunoma.KlientaID', '=', 'klienti.KlientaID')
            // Pievienojam kravas datus
            ->leftJoin('krava', 'vagonunoma.KravasID', '=', 'krava.KravasID')
            // Pievienojam vagona veida datus
            ->leftJoin('veidi', 'vagonunoma.VeidaID', '=', 'veidi.VeidaID')
            // Filtrējam nomas, kas pārklājas ar norādīto periodu
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

        // Atgriežam skatu ar datiem
        return view('NoslogojumsPeriods', compact('periodaSakums', 'periodaBeigas', 'periodaKopsavilkums', 'detali'));
    }
}
