<?php

namespace App\Http\Controllers;

use App\Models\Kravas;
use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda kravu ierakstu sarakstu un CRUD darbības.
class KravasController extends Controller
{
  // Pārbauda vai lietotājam nav administratora tiesību.
  private function userCannotModify()
  {
    return !auth()->check() || !auth()->user()->isAdmin();
  }

  // Kravu saraksts ar meklēšanu un kārtošanu.
  public function showAllKrava(Request $request)
  {
    // Meklēšanas parametri
    $search = trim((string) $request->query('search', ''));
    $vagonaId = trim((string) $request->query('vagona_id', ''));

    $kravasOptions = Kravas::query()
      ->select('Nosaukums')
      ->distinct()
      ->orderBy('Nosaukums')
      ->pluck('Nosaukums');

    // Kārtošanas parametri
    $sortBy = $request->query('sort_by', 'KravasID');
    $sortOrder = $request->query('sort_order', 'asc');
    
    // Atļauto kārtošanas lauku saraksts (drošībai)
    $allowedSortFields = [
      'Nosaukums',
      'VeidaID',
      'KravasID'
    ];
    
    // Pārbauda vai kārtošanas lauks ir atļauts
    if (!in_array($sortBy, $allowedSortFields)) {
      $sortBy = 'KravasID';
    }
    
    // Pārbauda kārtošanas virzienu
    if (!in_array($sortOrder, ['asc', 'desc'])) {
      $sortOrder = 'asc';
    }
    
    // Veidojam vaicājumu
    $query = Kravas::query();

    // Meklēšana pēc nosaukuma
    if ($search !== '') {
      $query->where('Nosaukums', 'like', '%' . $search . '%');
    }

    // Meklēšana pēc VagonaID (VeidaID)
    if ($vagonaId !== '') {
      $query->where('VeidaID', 'like', '%' . $vagonaId . '%');
    }

    $dati = $query
      ->with('veidi')
      ->orderBy($sortBy, $sortOrder)
      ->paginate(15)
      ->appends($request->query());
    
    return view('Kravas', compact('dati', 'sortBy', 'sortOrder', 'search', 'vagonaId', 'kravasOptions'));
  }

  // Dzēš kravu.
  public function delete($id)
  {
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst dzēst ierakstus.');
    }

    DB::table('krava')->where('KravasID', $id)->delete();
    return redirect('/Kravas')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
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
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
    }

    // Izveido jaunu kravas ierakstu.
    $kravas = new Kravas();
    $kravas->Nosaukums = $dati->input('Nosaukums');
    $kravas->VeidaID = $dati->input('VeidaID');
    $kravas->save();

    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika pievienots');
  }

  // Atver rediģēšanas formu.
  public function edit($id)
  {
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
    }

    $kravas = Kravas::find($id);
    $veidi = Veidi::all();

    return view('KravasEdit', compact('kravas', 'veidi'));
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
    }

    // Atjauno kravas ierakstu pēc ID.
    DB::table('krava')
      ->where('KravasID', $id)
      ->update([
        'Nosaukums' => $dati->input('Nosaukums'),
        'VeidaID' => $dati->input('VeidaID'),
      ]);

    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika atjaunināts');
  }
}
