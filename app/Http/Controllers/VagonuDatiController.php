<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VagonuDati;
use Illuminate\Support\Facades\DB;

class VagonuDatiController extends Controller
{
    public function showAllVagonuDati()
    {
     $dati= new VagonuDati();
     //dd($data->all());
       return view('VagonuDati', ['dati' => $dati->orderBy('DatuID', 'asc')->get()]);//
    }

    public function delete($id)
    {
      DB::table('vagonudati')->where('DatuID', $id)->delete();
      return redirect('/VagonuDati')->with('success', 'Ieraksts dzēsts');
    }

    public function create()
    {
      return view('VagonuDatUPiev');
    }

    public function details($id)
    {
      $datu = VagonuDati::find($id);
      return view('VagonuDatuApskate', ['vagonudati' => $datu]);
    }

    public function DatuSubmit(Request $dati)
    {
      


        $datu = new VagonuDati();
        $datu->NomasID = $dati->input('NomasID');
        $datu->VagonaID = $dati->input('VagonaID');
        // $darbiniekis->Admin = $dati->input('Admin');
        $datu->save();
        return redirect()->to('/VagonuDati')->with('success', 'Ieraksts pievienots');
    }

    public function edit($id)
    {
     $datu = VagonuDati::find($id);
       return view('VagonuDatuEdit', ['vagonudati' => $datu]);
    }



    public function editSubmit(Request $dati, $id)
    {




        DB::table('vagonudati')
            ->where('DatuID', $id)
            ->update([
                'NomasID' => $dati->input('NomasID'),
                'VagonaID' => $dati->input('VagonaID'),
            ]);

         return redirect()->to('/VagonuDati')->with('success', 'Ieraksts atjaunināts');
    }
}

