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
    // Nomas saraksts ar pagināciju.
    public function showAllNoma()
    {
        $noma = new Noma();
        return view('Noma', ['noma' => $noma->orderBy('NomasID', 'asc')->paginate(5)]);
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
