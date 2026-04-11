<?php

namespace App\Http\Controllers;

use App\Models\Amati;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda amata ierakstu CRUD darbības.
class AmataController extends Controller
{
  // Atļauj piekļuvi tikai administratoram.
  private function requireAdminAccess()
  {
    if (!auth()->check() || !auth()->user()->isAdmin()) {
      return redirect('/')->with('error', 'Piekļuve atļauta tikai administratoram.');
    }

    return null;
  }

  // Amatu saraksts.
  public function showAllAmati()
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    $amats = new Amati();
    return view('Amati', ['dati' => $amats->orderBy('AmataID', 'asc')->get()]);
  }

  // Dzēš amatu.
  public function delete($id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    DB::table('amats')->where('AmataID', $id)->delete();
    return redirect('/Amati')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    return view('AmataPiev');
  }

  // Saglabā jaunu amatu.
  public function DatuSubmit(Request $dati)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Izveido jaunu amata ierakstu.
    $amats = new Amati();
    $amats->Nosaukums = $dati->input('Nosaukums');
    $amats->save();

    return redirect()->to('/Amati')->with('success', 'Ieraksts tika pievienots');
  }

  // Atver rediģēšanas formu.
  public function edit($id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    $amats = Amati::find($id);
    return view('AmataEdit', ['amati' => $amats]);
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Atjauno tikai amata nosaukumu pēc ID.
    DB::table('amats')
      ->where('AmataID', $id)
      ->update([
        'Nosaukums' => $dati->input('Nosaukums'),
      ]);

    return redirect()->to('/Amati')->with('success', 'Ieraksts tika atjaunināts');
  }
}