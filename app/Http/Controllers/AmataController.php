<?php

namespace App\Http\Controllers;

use App\Models\Amati;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmataController extends Controller
{
  // Amatu saraksts.
  public function showAllAmati()
  {
    $amats = new Amati();
    return view('Amati', ['dati' => $amats->orderBy('AmataID', 'asc')->get()]);
  }

  // Dzēš amatu.
  public function delete($id)
  {
    DB::table('amats')->where('AmataID', $id)->delete();
    return redirect('/Amati')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    return view('AmataPiev');
  }

  // Parāda amata detaļas.
  public function details($id)
  {
    $amats = Amati::find($id);
    return view('AmataApskate', ['amati' => $amats]);
  }

  // Saglabā jaunu amatu.
  public function DatuSubmit(Request $dati)
  {
    $amats = new Amati();
    $amats->Nosaukums = $dati->input('Nosaukums');
    $amats->save();

    return redirect()->to('/Amati')->with('success', 'Ieraksts tika pievienots');
  }

  // Atver rediģēšanas formu.
  public function edit($id)
  {
    $amats = Amati::find($id);
    return view('AmataEdit', ['amati' => $amats]);
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    DB::table('amats')
      ->where('AmataID', $id)
      ->update([
        'Nosaukums' => $dati->input('Nosaukums'),
      ]);

    return redirect()->to('/Amati')->with('success', 'Ieraksts tika atjaunināts');
  }
}