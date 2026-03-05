<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kravas;
use Illuminate\Support\Facades\DB;

class KravasController extends Controller
{
    public function showAllKrava()

    {
     $kravas= new Kravas();
     //dd($data->all());
       return view('Kravas', ['dati' => $kravas->orderBy('KravasID', 'asc')->get()]);//
    }

    public function delete($id)
    {
      DB::table('krava')->where('KravasID', $id)->delete();
      return redirect('/Kravas')->with('success', 'Ieraksts dzēsts');
    }

    public function create()
    {
      return view('KravasPiev');
    }

    public function details($id)
    {
      $kravas = Kravas::find($id);
      return view('KravasApskate', ['kravas' => $kravas]);
    }

    public function KravasSubmit(Request $dati)
    {
      


        $kravas = new Kravas();
        $kravas->Nosaukums = $dati->input('Nosaukums');
        $kravas->save();
        return redirect()->to('/Krava')->with('success', 'Ieraksts pievienots');
    }

    public function edit($id)
    {
     $kravas = Kravas::find($id);
       return view('KravasEdit', ['kravas' => $kravas]);
    }



    public function editSubmit(Request $dati, $id)
    {




        DB::table('krava')
            ->where('KravasID', $id)
            ->update([
                'Nosaukums' => $dati->input('Nosaukums'),
            ]);

         return redirect()->to('/Kravas')->with('success', 'Ieraksts atjaunināts');
    }
}

