<?php

namespace App\Http\Controllers;

use App\Models\Kravas;
use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KravasController extends Controller
{
  // Kravu saraksts.
  public function showAllKrava()
  {
    $kravas = new Kravas();
    return view('Kravas', ['dati' => $kravas->orderBy('KravasID', 'asc')->get()]);
  }

  // Dzēš kravu.
  public function delete($id)
  {
    DB::table('krava')->where('KravasID', $id)->delete();
    return redirect('/Kravas')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    $veidi = Veidi::all();
    return view('KravasPiev', compact('veidi'));
  }

  // Parāda kravas detaļas.
  public function details($id)
  {
    $kravas = Kravas::find($id);
    return view('KravasApskate', ['kravas' => $kravas]);
  }

  // Saglabā jaunu kravu.
  public function DatuSubmit(Request $dati)
  {
    $kravas = new Kravas();
    $kravas->Nosaukums = $dati->input('Nosaukums');
    $kravas->VeidaID = $dati->input('VeidaID');
    $kravas->save();

    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika pievienots');
  }

  // Atver rediģēšanas formu.
  public function edit($id)
  {
    $kravas = Kravas::find($id);
    $veidi = Veidi::all();

    return view('KravasEdit', compact('kravas', 'veidi'));
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    DB::table('krava')
      ->where('KravasID', $id)
      ->update([
        'Nosaukums' => $dati->input('Nosaukums'),
        'VeidaID' => $dati->input('VeidaID'),
      ]);

    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika atjaunināts');
  }
}
