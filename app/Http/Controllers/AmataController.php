<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Amati;
use Illuminate\Support\Facades\DB;

class AmataController extends Controller
{
    public function showAllAmati()
    {
     $amats= new Amati();
     //dd($data->all());
       return view('Amati', ['dati' => $amats->orderBy('AmataID', 'asc')->get()]);//
    }

    public function delete($id)
    {
      DB::table('amats')->where('AmataID', $id)->delete();
      return redirect('/Amati')->with('success', 'Ieraksts dzēsts');
    }

    public function create()
    {
      return view('AmataPiev');
    }

    public function details($id)
    {
      $amats = Amati::find($id);
      return view('AmataApskate', ['amati' => $amats]);
    }

    public function DatuSubmit(Request $dati)
    {
      


        $amats = new Amati();
        $amats->Nosaukums = $dati->input('Nosaukums');
        $amats->save();
        return redirect()->to('/Amati')->with('success', 'Ieraksts pievienots');
    }

    public function edit($id)
    {
     $amats = Amati::find($id);
       return view('AmataEdit', ['amati' => $amats]);
    }



    public function editSubmit(Request $dati, $id)
    {




        DB::table('amats')
            ->where('AmataID', $id)
            ->update([
                'Nosaukums' => $dati->input('Nosaukums'),
            ]);

         return redirect()->to('/Amati')->with('success', 'Ieraksts atjaunināts');
    }
}

