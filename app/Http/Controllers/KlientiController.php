<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klienti;
use Illuminate\Support\Facades\DB;

class KlientiController extends Controller
{
    public function showAllKlienti()
    {
     $klientis= new Klienti();
     //dd($data->all());
       return view('Klienti', ['klientis' => $klientis->orderBy('KlientaID', 'asc')->get()]);
    }

    public function delete($id)
    {
      DB::table('klienti')->where('KlientaID', $id)->delete();
      return redirect('/Klienti')->with('success', 'Ieraksts dzēsts');
    }

    public function create()
    {
      return view('KlientuPiev');
    }

    public function details($id)
    {
      $klientis = Klienti::find($id);
      return view('KlientiApskate', ['klientis' => $klientis]);
    }

    public function KlientiSubmit(Request $dati)
    {
      


        $klientis = new Klienti();
        $klientis->Vards = $dati->input('Vards');
        $klientis->Uzvards = $dati->input('Uzvards');
        $klientis->Parole = $dati->input('Parole');
        $klientis->Epasts = $dati->input('Epasts');
        $klientis->TelefonaNumurs = $dati->input('TelefonaNumurs');
        $klientis->UznemumaNosaukums = $dati->input('UznemumaNosaukums');
        $klientis->JuridiskaAdrese = $dati->input('JuridiskaAdrese');
        $klientis->RegistracijasNumurs = $dati->input('RegistracijasNumurs');
        $klientis->KontaNumurs = $dati->input('KontaNumurs');



        // $klientis->Admin = $dati->input('Admin');
        $klientis->save();
        return redirect()->to('/Klienti')->with('success', 'Ieraksts pievienots');
    }

    public function edit($id)
    {
     $klientis = Klienti::find($id);
       return view('KlientiEdit', ['klientis' => $klientis]);
    }



    public function editSubmit(Request $dati, $id)
    {




        DB::table('klienti')
            ->where('KlientaID', $id)
            ->update([
                'Vards' => $dati->input('Vards'),
                'Uzvards' => $dati->input('Uzvards'),
                'Parole' => $dati->input('Parole'),
                'Epasts' => $dati->input('Epasts'),
                'TelefonaNumurs' => $dati->input('TelefonaNumurs'),
                'UznemumaNosaukums' => $dati->input('UznemumaNosaukums'),
                'JuridiskaAdrese' => $dati->input('JuridiskaAdrese'),
                'RegistracijasNumurs' => $dati->input('RegistracijasNumurs'),
                'KontaNumurs' => $dati->input('KontaNumurs'),
            ]);

         return redirect()->to('/Klienti')->with('success', 'Ieraksts atjaunināts');
    }
}

