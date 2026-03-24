<?php

namespace App\Http\Controllers;

use App\Models\Kravas;
use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KravasController extends Controller
{
  private function klientsCannotModify()
  {
    return auth()->check() && auth()->user()->isKlients();
  }

  // Kravu saraksts.
  public function showAllKrava()
  {
    $kravas = new Kravas();
    return view('Kravas', ['dati' => $kravas->orderBy('KravasID', 'asc')->get()]);
  }

  // Dzēš kravu.
  public function delete($id)
  {
    if ($this->klientsCannotModify()) {
      return redirect('/Kravas')->with('error', 'Klientam nav tiesību dzēst ierakstus.');
    }

    DB::table('krava')->where('KravasID', $id)->delete();
    return redirect('/Kravas')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    if ($this->klientsCannotModify()) {
      return redirect('/Kravas')->with('error', 'Klientam nav tiesību pievienot ierakstus.');
    }

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
    if ($this->klientsCannotModify()) {
      return redirect('/Kravas')->with('error', 'Klientam nav tiesību pievienot ierakstus.');
    }

    $kravas = new Kravas();
    $kravas->Nosaukums = $dati->input('Nosaukums');
    $kravas->VeidaID = $dati->input('VeidaID');
    $kravas->save();

    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika pievienots');
  }

  // Atver rediģēšanas formu.
  public function edit($id)
  {
    if ($this->klientsCannotModify()) {
      return redirect('/Kravas')->with('error', 'Klientam nav tiesību rediģēt ierakstus.');
    }

    $kravas = Kravas::find($id);
    $veidi = Veidi::all();

    return view('KravasEdit', compact('kravas', 'veidi'));
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    if ($this->klientsCannotModify()) {
      return redirect('/Kravas')->with('error', 'Klientam nav tiesību rediģēt ierakstus.');
    }

    DB::table('krava')
      ->where('KravasID', $id)
      ->update([
        'Nosaukums' => $dati->input('Nosaukums'),
        'VeidaID' => $dati->input('VeidaID'),
      ]);

    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika atjaunināts');
  }
}
