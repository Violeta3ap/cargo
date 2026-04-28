<?php

namespace App\Http\Controllers;

use App\Models\Amati;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * AmataController - Pārvalda amata ierakstu CRUD darbības
 * 
 * Šis kontrolieris atļauj administratoriem pārvaldīt amata (darba vietas) klasifikatorā:
 * - Apskatīt visus amatus
 * - Pievienot jaunus amatus
 * - Rediģēt esošus amatus
 * - Dzēst amatus
 */
class AmataController extends Controller
{
  /**
   * Pārbauda vai lietotājs ir autentificēts un ir administrators
   * 
   * @return null|Illuminate\Http\RedirectResponse
   *   Atgriež null, ja lietotājs ir administrators
   *   Atgriež 302 redirekciju uz sākumlapas, ja nav tiesību
   */
  private function requireAdminAccess()
  {
    // Pārbauda vai lietotājs ir autentificēts
    if (!auth()->check() || !auth()->user()->isAdmin()) {
      // Ja nav administrators, novirza uz sākumlapu ar kļūdas ziņojumu
      return redirect('/')->with('error', 'Piekļuve atļauta tikai administratoram.');
    }

    return null;
  }

  /**
   * Attēlo visu amatu sarakstu, kārtots alfabētiski
   * 
   * @return Illuminate\View\View
   */
  public function showAllAmati()
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Izveido jaunu Amati modeļa instanci un iegūst atbilstošus datus
    $amats = new Amati();
    // Atgriež skatu ar datiem, kārtotus pēc AmataID augošā secībā
    return view('Amati', ['dati' => $amats->orderBy('AmataID', 'asc')->get()]);
  }

  /**
   * Dzēš amatu pēc tā ID
   * 
   * @param int $id - Amata ID, ko dzēst
   * @return Illuminate\Http\RedirectResponse
   */
  public function delete($id)
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Dzēš ierakstu no tabulas "amats" pēc AmataID
    DB::table('amats')->where('AmataID', $id)->delete();
    
    // Novirza lietotāju atpakaļ uz amatu sarakstu ar apstiprinājuma ziņojumu
    return redirect('/Amati')->with('success', 'Ieraksts tika dzēsts');
  }

  /**
   * Atver pievienošanas formu jaunā amata izveidošanai
   * 
   * @return Illuminate\View\View
   */
  public function create()
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Atgriež skatu ar pievienošanas formu
    return view('AmataPiev');
  }

  /**
   * Saglabā jaunu amatu datus no formas
   * 
   * @param Illuminate\Http\Request $dati - Formas dati
   * @return Illuminate\Http\RedirectResponse
   */
  public function DatuSubmit(Request $dati)
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Izveido jaunu amata ierakstu
    $amats = new Amati();
    // Nolasa nosaukumu no formas ievades
    $amats->Nosaukums = $dati->input('Nosaukums');
    // Saglabā jauno ierakstu datu bāzē
    try {
      $amats->save();
    } catch (\Illuminate\Database\QueryException $e) {
      // Pārbauda vai kļūda ir saistīta ar datu garumu
      if (str_contains($e->getMessage(), 'Data too long for column')) {
        return back()->withInput()->withErrors(['database' => 'Ievadītie dati ir pārāk gari kādam no laukiem. Lūdzu, pārbaudiet un saīsiniet tekstu.']);
      }
      // Citas datu bāzes kļūdas
      return back()->withInput()->withErrors(['database' => 'Datu bāzes kļūda: ' . $e->getMessage()]);
    }

    // Novirza uz amatu sarakstu ar apstiprinājuma ziņojumu
    return redirect()->to('/Amati')->with('success', 'Ieraksts tika pievienots');
  }

  /**
   * Atver rediģēšanas formu esošam amatam
   * 
   * @param int $id - Amata ID, ko rediģēt
   * @return Illuminate\View\View
   */
  public function edit($id)
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Meklē amatu pēc ID
    $amats = Amati::find($id);
    // Atgriež rediģēšanas skatu ar amata datiem
    return view('AmataEdit', ['amati' => $amats]);
  }

  /**
   * Atjaunina amata datus datu bāzē
   * 
   * @param Illuminate\Http\Request $dati - Formas dati
   * @param int $id - Amata ID, ko atjaunināt
   * @return Illuminate\Http\RedirectResponse
   */
  public function editSubmit(Request $dati, $id)
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Atjaunina amata nosaukumu datu bāzē, meklējot pēc AmataID
    try {
      DB::table('amats')
        ->where('AmataID', $id)
        ->update([
          'Nosaukums' => $dati->input('Nosaukums'),
        ]);
    } catch (\Illuminate\Database\QueryException $e) {
      // Pārbauda vai kļūda ir saistīta ar datu garumu
      if (str_contains($e->getMessage(), 'Data too long for column')) {
        return back()->withInput()->withErrors(['database' => 'Ievadītie dati ir pārāk gari kādam no laukiem. Lūdzu, pārbaudiet un saīsiniet tekstu.']);
      }
      // Citas datu bāzes kļūdas
      return back()->withInput()->withErrors(['database' => 'Datu bāzes kļūda: ' . $e->getMessage()]);
    }

    // Novirza uz amatu sarakstu ar apstiprinājuma ziņojumu
    return redirect()->to('/Amati')->with('success', 'Ieraksts tika atjaunināts');
  }
}