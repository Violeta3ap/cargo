<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veidi;
use Illuminate\Support\Facades\DB;

class VeidiController extends Controller
{
    public function showAllVeidi()

    {
     $veidi= new Veidi();
     //dd($data->all());
       return view('Veidi', ['dati' => $veidi->orderBy('VeidaID', 'asc')->get()]);//
    }

    public function delete($id)
    {
      DB::table('veidi')->where('VeidaID', $id)->delete();
      return redirect('/Veidi')->with('success', 'Ieraksts tika dzēsts');
    }

    public function create()
    {
      return view('VeidiPiev');
    }

    public function details($id)
    {
      $veidi = Veidi::find($id);
      return view('VeidiApskate', ['veidi' => $veidi]);
    }

    public function DatuSubmit(Request $dati)
    {
      


        $veidi = new Veidi();
        $veidi->Nosaukums = $dati->input('Nosaukums');
        $veidi->save();
        return redirect()->to('/Veidi')->with('success', 'Ieraksts tika pievienots');
    }

    public function edit($id)
    {
     $veidi = Veidi::find($id);
       return view('VeidiEdit', ['veidi' => $veidi]);
    }



    public function editSubmit(Request $dati, $id)
    {




        DB::table('veidi')
            ->where('VeidaID', $id)
            ->update([
                'Nosaukums' => $dati->input('Nosaukums'),
            ]);

         return redirect()->to('/Veidi')->with('success', 'Ieraksts tika atjaunināts');
    }
}

