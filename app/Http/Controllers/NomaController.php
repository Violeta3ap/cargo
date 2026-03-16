<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noma;
use Illuminate\Support\Facades\DB;
use App\Models\Klienti;
use App\Models\Darbinieki;
use App\Models\Kravas;


class NomaController extends Controller
{
    public function showAllNoma()
    {
     $noma= new Noma();
     //dd($data->all());
       return view('Noma', ['noma' => $noma->orderBy('NomasID', 'asc')->get()]);
    }

    public function delete($id)
    {
      DB::table('vagonunoma')->where('NomasID', $id)->delete();
      return redirect('/Noma')->with('success', 'Ieraksts tika dzēsts');
    }

public function create()
{
    $klientis = Klienti::orderBy('KlientaID','asc')->get();
    $darbinieki = Darbinieki::orderBy('DarbiniekaID','asc')->get();
    $kravas = Kravas::orderBy('KravasID','asc')->get();

    return view('NomaPiev', [
        'klienti' => $klientis,
        'darbinieki' => $darbinieki,
        'kravas' => $kravas
    ]);
}

    public function details($id)
    {
      $noma = Noma::find($id);
      return view('NomaApskate', ['noma' => $noma]);
    }

    public function NomaSubmit(Request $dati)
    {
      


        $noma = new Noma();
        $noma->KlientaID = $dati->input('KlientaID');
        $noma->DarbiniekaID = $dati->input('DarbiniekaID');
        $noma->KravasID = $dati->input('KravasID');
        $noma->VagonuSkaits = $dati->input('VagonuSkaits');
        $noma->NomasSakumaPeriods = $dati->input('NomasSakumaPeriods');
        $noma->NomasBeiguPeriods = $dati->input('NomasBeiguPeriods');
        $noma->NosutisanasStacija = $dati->input('NosutisanasStacija');
        $noma->Galastacija = $dati->input('Galastacija');
        // $noma->KopejaMaksa = 0;
        $noma->KopejaMaksa = $dati->input('KopejaMaksa');
        // $darbiniekis->Admin = $dati->input('Admin');
        $noma->save();
        return redirect()->to('/Noma')->with('success', 'Ieraksts tika pievienots');
    }

 public function edit($id)
{
    $noma = Noma::find($id);

    $klientis = Klienti::orderBy('KlientaID','asc')->get();
    $darbinieki = Darbinieki::orderBy('DarbiniekaID','asc')->get();
    $kravas = Kravas::orderBy('KravasID','asc')->get();

    return view('NomaEdit', [
        'noma' => $noma,
        'klienti' => $klientis,
        'darbinieki' => $darbinieki,
        'kravas' => $kravas
    ]);
}



    public function editSubmit(Request $dati, $id)
    {




        DB::table('vagonunoma')
            ->where('NomasID', $id)
            ->update([
                'KlientaID' => $dati->input('KlientaID'),
                'DarbiniekaID' => $dati->input('DarbiniekaID'),
                'KravasID' => $dati->input('KravasID'),
                'VagonuSkaits' => $dati->input('VagonuSkaits'),
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
                'NosutisanasStacija' => $dati->input('NosutisanasStacija'),
                'Galastacija' => $dati->input('Galastacija'),
                'KopejaMaksa' => $dati->input('KopejaMaksa'),
            ]);

         return redirect()->to('/Noma')->with('success', 'Ieraksts tika atjaunināts');
    }



}








