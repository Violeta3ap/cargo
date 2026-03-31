<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klienti;
use Illuminate\Support\Facades\DB;

// Pārvalda klientu ierakstu sarakstu un CRUD darbības.
class KlientiController extends Controller
{
  // Atļauj piekļuvi tikai administratoram.
  private function requireAdminAccess()
  {
    if (!auth()->check() || !auth()->user()->isAdmin()) {
      return redirect('/')->with('error', 'Piekļuve atļauta tikai administratoram.');
    }

    return null;
  }

  // Klientu saraksts ar pagināciju, meklēšanu un kārtošanu.
  public function showAllKlienti(Request $request)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Nolasa filtrēšanas vērtības no URL parametriem.
    $vards = trim((string) $request->query('vards', ''));
    $uzvards = trim((string) $request->query('uzvards', ''));
    $uznemumanos = trim((string) $request->query('uznemumanos', ''));
    
    // Kārtošanas parametri
    $sortBy = $request->query('sort_by', 'KlientaID');
    $sortOrder = $request->query('sort_order', 'asc');
    
    // Atļauto kārtošanas lauku saraksts (drošībai)
    $allowedSortFields = [
      'Vards', 
      'Uzvards', 
      'Epasts', 
      'TelefonaNumurs', 
      'UznemumaNosaukums', 
      'JuridiskaAdrese', 
      'RegistracijasNumurs', 
      'KontaNumurs',
      'KlientaID'
    ];
    
    // Pārbauda vai kārtošanas lauks ir atļauts
    if (!in_array($sortBy, $allowedSortFields)) {
      $sortBy = 'KlientaID';
    }
    
    // Pārbauda kārtošanas virzienu
    if (!in_array($sortOrder, ['asc', 'desc'])) {
      $sortOrder = 'asc';
    }

    $query = Klienti::query();

    // Pievieno filtrus tikai ja tie ir aizpildīti.
    if ($vards !== '') {
      $query->where('Vards', 'like', '%' . $vards . '%');
    }

    if ($uzvards !== '') {
      $query->where('Uzvards', 'like', '%' . $uzvards . '%');
    }

    if ($uznemumanos !== '') {
      $query->where('UznemumaNosaukums', 'like', '%' . $uznemumanos . '%');
    }

    $klientis = $query
      ->orderBy($sortBy, $sortOrder)
      ->paginate(15)
      ->appends($request->query());

    return view('Klienti', compact('klientis', 'vards', 'uzvards', 'uznemumanos', 'sortBy', 'sortOrder'));
  }

  // Dzēš klienta ierakstu.
  public function delete($id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    $hasRentals = DB::table('vagonunoma')->where('KlientaID', $id)->exists();
    if ($hasRentals) {
      return redirect('/Klienti')->with('error', 'Klientu ar izveidotu nomu dzēst nedrīkst.');
    }

    DB::table('klienti')->where('KlientaID', $id)->delete();
    return redirect('/Klienti')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    return view('KlientuPiev');
  }

  // Parāda klienta detaļas.
  public function details($id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    $klientis = Klienti::find($id);
    return view('KlientiApskate', ['klientis' => $klientis]);
  }

  // Saglabā jaunu klientu.
  public function KlientiSubmit(Request $dati)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Izveido jaunu klienta ierakstu.
    $klientis = new Klienti();
    $klientis->Vards = $dati->input('Vards');
    $klientis->Uzvards = $dati->input('Uzvards');
    $klientis->Epasts = $dati->input('Epasts');
    $klientis->TelefonaNumurs = $dati->input('TelefonaNumurs');
    $klientis->UznemumaNosaukums = $dati->input('UznemumaNosaukums');
    $klientis->JuridiskaAdrese = $dati->input('JuridiskaAdrese');
    $klientis->RegistracijasNumurs = $dati->input('RegistracijasNumurs');
    $klientis->KontaNumurs = $dati->input('KontaNumurs');
    $klientis->save();

    return redirect()->to('/Klienti')->with('success', 'Ieraksts tika pievienots');
  }

  // Atver rediģēšanas formu.
  public function edit($id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    $klientis = Klienti::find($id);
    return view('KlientiEdit', ['klientis' => $klientis]);
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Atjauno klienta laukus pēc ID.
    DB::table('klienti')
      ->where('KlientaID', $id)
      ->update([
        'Vards' => $dati->input('Vards'),
        'Uzvards' => $dati->input('Uzvards'),
        'Epasts' => $dati->input('Epasts'),
        'TelefonaNumurs' => $dati->input('TelefonaNumurs'),
        'UznemumaNosaukums' => $dati->input('UznemumaNosaukums'),
        'JuridiskaAdrese' => $dati->input('JuridiskaAdrese'),
        'RegistracijasNumurs' => $dati->input('RegistracijasNumurs'),
        'KontaNumurs' => $dati->input('KontaNumurs'),
      ]);

    return redirect()->to('/Klienti')->with('success', 'Ieraksts tika atjaunināts');
  }
}
