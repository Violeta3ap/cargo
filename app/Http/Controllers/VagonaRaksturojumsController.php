<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VagonaRaksturojums;
use Illuminate\Support\Facades\DB;

class VagonaRaksturojumsController extends Controller
{
    public function showAllVagonaRaksturojums()
    {
     $dati= new VagonaRaksturojums();
     //dd($data->all());
       return view('VagonaRaksturojums', ['dati' => $dati->orderBy('VagonaID', 'asc')->get()]);//
    }

    public function delete($id)
    {
      DB::table('vagonaraksturojums')->where('VagonaID', $id)->delete();
      return redirect('/VagonaRaksturojums')->with('success', 'Ieraksts dzēsts');
    }

    public function create()
    {
      return view('VagonaRaksturojumaPiev');
    }

    public function details($id)
    {
      $raksturojums = VagonaRaksturojums::find($id);
      return view('VagonaRaksturojumaApskate', ['vagonaraksturojums' => $raksturojums]);
    }

    public function DatuSubmit(Request $dati)
    {
      


        $raksturojums = new VagonaRaksturojums();
        $raksturojums->VeidaID = $dati->input('VeidaID');
        $raksturojums->KravasID = $dati->input('KravasID');
        $raksturojums->Celtspeja = $dati->input('Celtspeja');
        $raksturojums->VagonaNumurs = $dati->input('VagonaNumurs');
        // $darbiniekis->Admin = $dati->input('Admin');
        $raksturojums->save();
        return redirect()->to('/VagonaRaksturojums')->with('success', 'Ieraksts pievienots');
    }

    public function edit($id)
    {
     $raksturojums = VagonaRaksturojums::find($id);
       return view('VagonaRaksturojumaEdit', ['vagonaraksturojums' => $raksturojums]);
    }



    public function editSubmit(Request $dati, $id)
    {

        DB::table('vagonaraksturojums')
            ->where('VagonaID', $id)
            ->update([
                'VeidaID' => $dati->input('VeidaID'),
                'KravasID' => $dati->input('KravasID'),
                'Celtspeja' => $dati->input('Celtspeja'),
                'VagonaNumurs' => $dati->input('VagonaNumurs'),
            ]);

         return redirect()->to('/VagonaRaksturojums')->with('success', 'Ieraksts atjaunināts');
    }
}

