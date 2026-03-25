<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noma;
use Illuminate\Support\Facades\DB;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;

class NomaController extends Controller
{
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

    // Nomas saraksts ar pagināciju un meklēšanu.
    public function showAllNoma(Request $request)
    {
        $klients = trim((string) $request->query('klients', ''));


        $query = Noma::query()
            ->with(['klienti'])
            ->leftJoin('klienti', 'vagonunoma.KlientaID', '=', 'klienti.Vards')
            ->leftJoin('klienti', 'vagonunoma.KravasID', '=', 'klienti.Uzvards')
            ->leftJoin('klienti', 'vagonunoma.VeidaID', '=', 'klienti.UznemumaNosaukums')
            ->select('vagonunoma.*');

        if ($klients !== '') {
            $query->where('klienti.Vards', 'like', '%' . $klients . '%');
        }

        if ($klients !== '') {
            $query->where('klienti.Uzvards', 'like', '%' . $klients . '%');
        }

        if ($klients !== '') {
            $query->where('klienti.UznemumaNosaukums', 'like', '%' . $klients . '%');
        }

        $noma = $query
            ->orderBy('vagonunoma.NomasID', 'asc')
            ->paginate(5)
            ->appends($request->query());

        return view('Noma', compact('klients'));
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
            'Svars' => ['required', 'numeric', 'min:1'],
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
        $noma->Svars = $dati->input('Svars');
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
            'Svars' => ['required', 'numeric', 'min:1'],
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
                'Svars' => $dati->input('Svars'),
                'VeidaID' => $dati->input('VeidaID'),
                'VagonuSkaits' => $dati->input('VagonuSkaits'),
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
                'KopejaMaksa' => $dati->input('KopejaMaksa'),
            ]);

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika atjaunināts');
    }
}
