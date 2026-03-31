<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda noslogojuma datu attēlošanu un sinhronizāciju.
class NoslogojumsController extends Controller
{
    // Atļauj piekļuvi tikai administratoram.
    private function requireAdminAccess()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/Noma')->with('error', 'Noslogojuma dati pieejami tikai administratoram.');
        }

        return null;
    }

    // Attēlo noslogojuma lapu ar datuma filtru.
    public function show(Request $request)
    {
        if ($response = $this->requireAdminAccess()) {
            return $response;
        }

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

        // Detalizētais saraksts: katrs aktīvs nomas ieraksts
        $detali = DB::table('noslogojums')
            ->join('vagonunoma', 'noslogojums.NomasID', '=', 'vagonunoma.NomasID')
            ->join('veidi', 'noslogojums.VeidaID', '=', 'veidi.VeidaID')
            ->where('noslogojums.NomasSakumaPeriods', '<=', $datums)
            ->where('noslogojums.NomasBeiguPeriods', '>=', $datums)
            ->select(
                'noslogojums.NomasID',
                'noslogojums.NomasSakumaPeriods',
                'noslogojums.NomasBeiguPeriods',
                'veidi.Nosaukums as VeidaNosaukums',
                'vagonunoma.VagonuSkaits'
            )
            ->orderBy('noslogojums.NomasSakumaPeriods')
            ->get();

        return view('Noslogojums', compact('kopsavilkums', 'detali', 'datums'));
    }

    // Sinhronizē noslogojums tabulu ar vagonunoma (tikai adminam/darbiniekam).
    public function syncAll()
    {
        if ($response = $this->requireAdminAccess()) {
            return $response;
        }

        // Nolasa visas nomas un sinhronizē ar noslogojuma tabulu.
        $nomas = DB::table('vagonunoma')->get();

        foreach ($nomas as $noma) {
            DB::table('noslogojums')->updateOrInsert(
                ['NomasID' => $noma->NomasID],
                [
                    'NomasSakumaPeriods' => $noma->NomasSakumaPeriods,
                    'NomasBeiguPeriods'  => $noma->NomasBeiguPeriods,
                    'VeidaID'           => $noma->VeidaID,
                ]
            );
        }

        return redirect('/Noslogojums')->with('success', 'Visi dati sinhronizēti veiksmīgi.');
    }
}
