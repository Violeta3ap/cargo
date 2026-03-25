<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klienti;
use Illuminate\Support\Facades\DB;

class KlientiController extends Controller
{
  // Klientu saraksts ar pagināciju, meklēšanu un kārtošanu.
  public function showAllKlienti(Request $request)
  {
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
      ->paginate(5)
      ->appends($request->query());

    return view('Klienti', compact('klientis', 'vards', 'uzvards', 'uznemumanos', 'sortBy', 'sortOrder'));
  }

  // Dzēš klienta ierakstu.
  public function delete($id)
  {
    DB::table('klienti')->where('KlientaID', $id)->delete();
    return redirect('/Klienti')->with('success', 'Ieraksts tika dzēsts');
  }

  // Atver pievienošanas formu.
  public function create()
  {
    return view('KlientuPiev');
  }

  // Parāda klienta detaļas.
  public function details($id)
  {
    $klientis = Klienti::find($id);
    return view('KlientiApskate', ['klientis' => $klientis]);
  }

  // Saglabā jaunu klientu.
  public function KlientiSubmit(Request $dati)
  {
    $klientis = new Klienti();
    $klientis->Vards = $dati->input('Vards');
    $klientis->Uzvards = $dati->input('Uzvards');
    $klientis->Parole = $dati->input('Parole');
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
    $klientis = Klienti::find($id);
    return view('KlientiEdit', ['klientis' => $klientis]);
  }

  // Saglabā rediģētas vērtības.
  public function editSubmit(Request $dati, $id)
  {
    DB::table('klienti')
      ->where('KlientaID', $id)
      ->update([
        'Vards' => $dati->input('Vards'),
        'Uzvards' => $dati->input('Uzvards'),
        'Parole' => $dati->input('Parole'),
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