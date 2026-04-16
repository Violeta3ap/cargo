<?php

namespace App\Http\Controllers;

use App\Models\Kravas;
use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * KravasController - Pārvalda kravu (kravas tipu) ierakstu sarakstu un CRUD darbības
 * 
 * Šis kontrolieris atļauj administratoriem pārvaldīt kravu datus:
 * - Apskatīt kravu sarakstu ar filtrēšanu, meklēšanu un kārtošanu
 * - Pievienot jaunas kravas
 * - Rediģēt kravu informāciju
 * - Dzēst kravas
 */
class KravasController extends Controller
{
  /**
   * Normalizē kravu nosaukuma vērtības - noņem ciparus un speciālos simbolus
   * Rezultāts: Tikai burti
   * 
   * @param string|null $value - Ievades vērtība
   * @return string - Normalizēta vērtība
   */
  private function normalizeNameValue(?string $value): string
  {
    // Noņem visus simbolus, izņemot burtus (\p{L})
    $value = (string) preg_replace('/[^\p{L}]/u', '', trim((string) $value));
    return $value;
  }

  /**
   * Pārbauda vai lietotājam nav administratora tiesību
   * 
   * @return bool - true, ja lietotājs nav administrators
   */
  private function userCannotModify()
  {
    // Atgriež true, ja lietotājs nav autentificēts vai nav administrators
    return !auth()->check() || !auth()->user()->isAdmin();
  }

  /**
   * Attēlo kravu sarakstu ar meklēšanu un kārtošanu
   * 
   * @param Illuminate\Http\Request $request - Pieprasījums ar query parametriem
   * @return Illuminate\View\View - Skats ar kravu sarakstu
   */
  public function showAllKrava(Request $request)
  {
    // Nolasa meklēšanas parametrus no URL
    $search = trim((string) $request->query('search', ''));
    $vagonaNosaukums = trim((string) $request->query('vagona_nosaukums', ''));

    // Iegūst visus unikālos kravu nosaukumus (for dropdown filter)
    $kravasOptions = Kravas::query()
      ->select('Nosaukums')
      ->distinct()
      ->orderBy('Nosaukums')
      ->pluck('Nosaukums');

    // Kārtošanas parametri - Par kuriem laukiem un kārtībā
    $sortBy = $request->query('sort_by', 'KravasID');
    $sortOrder = $request->query('sort_order', 'asc');
    
    // Atļauto kārtošanas lauku saraksts (drošībai)
    $allowedSortFields = [
      'Nosaukums',
      'VeidaID',
      'KravasID'
    ];
    
    // Pārbauda vai kārtošanas lauks ir draudzīgs
    if (!in_array($sortBy, $allowedSortFields)) {
      $sortBy = 'KravasID';
    }
    
    // Pārbauda kārtošanas virzienu
    if (!in_array($sortOrder, ['asc', 'desc'])) {
      $sortOrder = 'asc';
    }
    
    // Sāk veidot vaicājumu
    $query = Kravas::query();

    // Filtrēšana pēc kravu nosaukuma (precīza atbilstība)
    if ($search !== '') {
      $query->where('Nosaukums', $search);
    }

    // Meklēšana pēc vagona veida nosaukuma (partial matching)
    if ($vagonaNosaukums !== '') {
      $query->whereHas('veidi', function ($builder) use ($vagonaNosaukums) {
        $builder->where('Nosaukums', 'like', '%' . $vagonaNosaukums . '%');
      });
    }

    // Izpilda vaicājumu ar kārtošanu un pagināciju
    $dati = $query
      ->with('veidi') // Pieveido saistītā vagona veida informāciju
      ->orderBy($sortBy, $sortOrder)
      ->paginate(15)
      ->appends($request->query()); // Pievieno filtru parametrus paginācijas linkos
    
    // Atgriež skatu ar datiem
    return view('Kravas', compact('dati', 'sortBy', 'sortOrder', 'search', 'vagonaNosaukums', 'kravasOptions'));
  }

  /**
   * Dzēš kravu pēc tās ID
   * 
   * @param int $id - Kravas ID dzēšanai
   * @return Illuminate\Http\RedirectResponse
   */
  public function delete($id)
  {
    // Pārbauda vai lietotājs var pieņemt izmaiņas
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst dzēst ierakstus.');
    }

    // Dzēš kravu no tabulas "krava" pēc KravasID
    DB::table('krava')->where('KravasID', $id)->delete();
    // Novirza uz kravu sarakstu ar apstiprinājuma ziņojumu
    return redirect('/Kravas')->with('success', 'Ieraksts tika dzēsts');
  }

  /**
   * Atver pievienošanas formu jaunai kravai
   * 
   * @return Illuminate\View\View
   */
  public function create()
  {
    // Pārbauda vai lietotājs var pieņemt izmaiņas
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
    }

    // Iegūst visus vagona veidus (for dropdown)
    $veidi = Veidi::all();
    // Atgriež skatu ar pievienošanas formu
    return view('KravasPiev', compact('veidi'));
  }

  /**
   * Saglabā jaunu kravu datus no formas
   * 
   * @param Illuminate\Http\Request $dati - Formas dati
   * @return Illuminate\Http\RedirectResponse
   */
  public function DatuSubmit(Request $dati)
  {
    // Pārbauda vai lietotājs var pieņemt izmaiņas
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
    }

    // Normalizē kravu nosaukumu (noņem speciālos simbolus)
    $dati->merge([
      'Nosaukums' => $this->normalizeNameValue($dati->input('Nosaukums')),
    ]);

    // Validē formas datus
    $dati->validate([
      'Nosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{L}+$/u'],
      'VeidaID' => ['required', 'integer'],
    ], [
      'Nosaukums.required' => 'Lauks "Nosaukums" ir obligāts.',
      'Nosaukums.regex' => 'Laukā "Nosaukums" drīkst ievadīt tikai burtus.',
      'VeidaID.required' => 'Lauks "Vagona veida nosaukums" ir obligāts.',
    ]);

    // Izveido jaunu kravu ierakstu
    $kravas = new Kravas();
    $kravas->Nosaukums = $dati->input('Nosaukums');
    $kravas->VeidaID = $dati->input('VeidaID');
    // Saglabā jauno ierakstu datu bāzē
    $kravas->save();

    // Novirza uz kravu sarakstu ar apstiprinājuma ziņojumu
    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika pievienots');
  }

  /**
   * Atver rediģēšanas formu esošai kravai
   * 
   * @param int $id - Kravas ID, ko rediģēt
   * @return Illuminate\View\View
   */
  public function edit($id)
  {
    // Pārbauda vai lietotājs var pieņemt izmaiņas
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
    }

    // Meklē kravu pēc ID
    $kravas = Kravas::find($id);
    // Iegūst visus vagona veidus (for dropdown)
    $veidi = Veidi::all();

    // Atgriež rediģēšanas skatu ar kravu datiem
    return view('KravasEdit', compact('kravas', 'veidi'));
  }

  /**
   * Atjaunina kravu datus datu bāzē
   * 
   * @param Illuminate\Http\Request $dati - Formas dati
   * @param int $id - Kravas ID, ko atjaunināt
   * @return Illuminate\Http\RedirectResponse
   */
  public function editSubmit(Request $dati, $id)
  {
    // Pārbauda vai lietotājs var pieņemt izmaiņas
    if ($this->userCannotModify()) {
      return redirect('/Kravas')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
    }

    // Normalizē kravu nosaukumu
    $dati->merge([
      'Nosaukums' => $this->normalizeNameValue($dati->input('Nosaukums')),
    ]);

    // Validē formas datus
    $dati->validate([
      'Nosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{L}+$/u'],
      'VeidaID' => ['required', 'integer'],
    ], [
      'Nosaukums.required' => 'Lauks "Nosaukums" ir obligāts.',
      'Nosaukums.regex' => 'Laukā "Nosaukums" drīkst ievadīt tikai burtus.',
      'VeidaID.required' => 'Lauks "Vagona veida nosaukums" ir obligāts.',
    ]);

    // Atjaunina kravu ierakstu datu bāzē pēc ID
    DB::table('krava')
      ->where('KravasID', $id)
      ->update([
        'Nosaukums' => $dati->input('Nosaukums'),
        'VeidaID' => $dati->input('VeidaID'),
      ]);

    // Novirza uz kravu sarakstu ar apstiprinājuma ziņojumu
    return redirect()->to('/Kravas')->with('success', 'Ieraksts tika atjaunināts');
  }
}
