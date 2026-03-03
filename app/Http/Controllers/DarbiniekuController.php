<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Darbinieki;
use Illuminate\Support\Facades\DB;

class DarbiniekuController extends Controller
{
    public function showAllDarbinieki()
    {
     $darbiniekis= new Darbinieki();
     //dd($data->all());
       return view('Darbinieki', ['darbiniekis' => $darbiniekis->orderBy('DarbiniekaID', 'asc')->get()]);
    }

    public function delete($id)
    {
      DB::table('darbinieki')->where('DarbiniekaID', $id)->delete();
      return redirect('/Darbinieki')->with('success', 'Ieraksts dzēsts');
    }

    public function create()
    {
      return view('DarbiniekuPiev');
    }

    public function details($id)
    {
      $darbiniekis = Darbinieki::find($id);
      return view('DarbiniekiApskate', ['darbinieki' => $darbiniekis]);
    }

    public function DarbiniekiSubmit(Request $dati)
    {
      


        $darbiniekis = new Darbinieki();
        $darbiniekis->Vards = $dati->input('Vards');
        $darbiniekis->Uzvards = $dati->input('Uzvards');
        $darbiniekis->Parole = $dati->input('Parole');
        $darbiniekis->Epasts = $dati->input('Epasts');
        $darbiniekis->TelefonaNumurs = $dati->input('TelefonaNumurs');
        $darbiniekis->AmataID = $dati->input('AmataID');
        // $darbiniekis->Admin = $dati->input('Admin');
        $darbiniekis->save();
        return redirect()->to('/Darbinieki')->with('success', 'Ieraksts pievienots');
    }

    public function edit($id)
    {
     $darbiniekis = Darbinieki::find($id);
       return view('DarbiniekiEdit', ['darbinieki' => $darbiniekis]);
    }



    public function editSubmit(Request $dati, $id)
    {




        DB::table('darbinieki')
            ->where('DarbiniekaID', $id)
            ->update([
                'Vards' => $dati->input('Vards'),
                'Uzvards' => $dati->input('Uzvards'),
                'Parole' => $dati->input('Parole'),
                'Epasts' => $dati->input('Epasts'),
                'TelefonaNumurs' => $dati->input('TelefonaNumurs'),
                'AmataID' => $dati->input('AmataID'),
            ]);

         return redirect()->to('/Darbinieki')->with('success', 'Ieraksts atjaunināts');
    }
}

