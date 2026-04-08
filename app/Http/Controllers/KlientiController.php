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

  private function normalizeLettersOnlyValue(?string $value): string
  {
    $value = (string) preg_replace('/[^\p{L}\s]/u', '', trim((string) $value));
    $value = (string) preg_replace('/\s+/u', ' ', $value);

    if ($value === '') {
      return '';
    }

    return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
  }

  private function normalizeAddressValue(?string $value): string
  {
    $value = (string) preg_replace('/[^0-9\p{L}\s\.,\-\/]/u', '', trim((string) $value));
    $value = (string) preg_replace('/\s+/u', ' ', $value);

    if ($value === '') {
      return '';
    }

    return mb_strtoupper(mb_substr($value, 0, 1, 'UTF-8'), 'UTF-8')
      . mb_substr($value, 1, null, 'UTF-8');
  }

  private function normalizeAccountNumber(?string $value): string
  {
    return strtoupper((string) preg_replace('/[^A-Z0-9]/i', '', trim((string) $value)));
  }

  private function normalizedClientData(Request $dati): array
  {
    return [
      'Vards' => $this->normalizeLettersOnlyValue($dati->input('Vards')),
      'Uzvards' => $this->normalizeLettersOnlyValue($dati->input('Uzvards')),
      'Epasts' => strtolower(trim((string) $dati->input('Epasts'))),
      'TelefonaNumurs' => (string) preg_replace('/\D+/', '', (string) $dati->input('TelefonaNumurs')),
      'UznemumaNosaukums' => $this->normalizeLettersOnlyValue($dati->input('UznemumaNosaukums')),
      'JuridiskaAdrese' => $this->normalizeAddressValue($dati->input('JuridiskaAdrese')),
      'RegistracijasNumurs' => (string) preg_replace('/\D+/', '', (string) $dati->input('RegistracijasNumurs')),
      'KontaNumurs' => $this->normalizeAccountNumber($dati->input('KontaNumurs')),
    ];
  }

  private function clientValidationRules(): array
  {
    return [
      'Vards' => ['required', 'string', 'max:50', 'regex:/^\p{Lu}[\p{L}\s]*$/u'],
      'Uzvards' => ['required', 'string', 'max:50', 'regex:/^\p{Lu}[\p{L}\s]*$/u'],
      'Epasts' => ['required', 'email', 'max:255'],
      'TelefonaNumurs' => ['required', 'digits:8'],
      'UznemumaNosaukums' => ['required', 'string', 'max:100', 'regex:/^\p{Lu}[\p{L}\s]*$/u'],
      'JuridiskaAdrese' => ['required', 'string', 'max:255', 'regex:/^\p{Lu}[0-9\p{L}\s\.,\-\/]*$/u'],
      'RegistracijasNumurs' => ['required', 'string', 'max:20', 'regex:/^\d+$/'],
      'KontaNumurs' => ['required', 'string', 'max:34', 'regex:/^[A-Z]+[0-9]+$/'],
    ];
  }

  private function clientValidationMessages(): array
  {
    return [
      'Vards.required' => 'Lauks "Vārds" ir obligāts.',
      'Vards.regex' => 'Laukā "Vārds" drīkst ievadīt tikai burtus, un pirmajam burtam jābūt lielajam.',
      'Uzvards.required' => 'Lauks "Uzvārds" ir obligāts.',
      'Uzvards.regex' => 'Laukā "Uzvārds" drīkst ievadīt tikai burtus, un pirmajam burtam jābūt lielajam.',
      'Epasts.required' => 'Lauks "E-pasts" ir obligāts.',
      'Epasts.email' => 'Laukā "E-pasts" jāievada derīga e-pasta adrese ar simbolu @.',
      'TelefonaNumurs.required' => 'Lauks "Telefona numurs" ir obligāts.',
      'TelefonaNumurs.digits' => 'Laukā "Telefona numurs" drīkst ievadīt tikai 8 ciparus.',
      'UznemumaNosaukums.required' => 'Lauks "Uzņēmuma nosaukums" ir obligāts.',
      'UznemumaNosaukums.regex' => 'Laukā "Uzņēmuma nosaukums" drīkst ievadīt tikai burtus, un pirmajam burtam jābūt lielajam.',
      'JuridiskaAdrese.required' => 'Lauks "Juridiskā adrese" ir obligāts.',
      'JuridiskaAdrese.regex' => 'Laukā "Juridiskā adrese" drīkst būt burti un cipari, un pirmajam simbolam jābūt lielajam burtam.',
      'RegistracijasNumurs.required' => 'Lauks "Reģistrācijas numurs" ir obligāts.',
      'RegistracijasNumurs.regex' => 'Laukā "Reģistrācijas numurs" drīkst ievadīt tikai ciparus.',
      'KontaNumurs.required' => 'Lauks "Konta numurs" ir obligāts.',
      'KontaNumurs.regex' => 'Laukam "Konta numurs" jāsākas ar lielo burtu, pēc kura seko cipari.',
    ];
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
    $search = trim((string) $request->query('search', ''));
    
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

    if ($search !== '') {
      $query->where(function ($builder) use ($search) {
        $builder->where('Vards', 'like', '%' . $search . '%')
          ->orWhere('Uzvards', 'like', '%' . $search . '%')
          ->orWhere('UznemumaNosaukums', 'like', '%' . $search . '%');
      });
    }

    $klientis = $query
      ->orderBy($sortBy, $sortOrder)
      ->paginate(15)
      ->appends($request->query());

    return view('Klienti', compact('klientis', 'vards', 'uzvards', 'uznemumanos', 'search', 'sortBy', 'sortOrder'));
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

    $dati->merge($this->normalizedClientData($dati));
    $dati->validate($this->clientValidationRules(), $this->clientValidationMessages());

    $vards = trim((string) $dati->input('Vards'));
    $uzvards = trim((string) $dati->input('Uzvards'));
    $epasts = trim((string) $dati->input('Epasts'));
    $telefonaNumurs = trim((string) $dati->input('TelefonaNumurs'));
    $uznemumaNosaukums = trim((string) $dati->input('UznemumaNosaukums'));
    $juridiskaAdrese = trim((string) $dati->input('JuridiskaAdrese'));
    $registracijasNumurs = trim((string) $dati->input('RegistracijasNumurs'));
    $kontaNumurs = trim((string) $dati->input('KontaNumurs'));

    $duplicateExists = Klienti::query()
      ->where(function ($query) use ($epasts, $telefonaNumurs, $uznemumaNosaukums, $juridiskaAdrese, $registracijasNumurs, $kontaNumurs) {
        $query->whereRaw('LOWER(Epasts) = ?', [strtolower($epasts)])
          ->orWhere('TelefonaNumurs', $telefonaNumurs)
          ->orWhere('UznemumaNosaukums', $uznemumaNosaukums)
          ->orWhere('JuridiskaAdrese', $juridiskaAdrese)
          ->orWhere('RegistracijasNumurs', $registracijasNumurs)
          ->orWhere('KontaNumurs', $kontaNumurs);
      })
      ->exists();

    if ($duplicateExists) {
      return back()->withInput()->withErrors(['duplicate' => 'Klienta dati jau eksistē. Lūdzu, izmainiet laukus un mēģiniet vēlreiz.']);
    }

    // Izveido jaunu klienta ierakstu.
    $klientis = new Klienti();
    $klientis->Vards = $vards;
    $klientis->Uzvards = $uzvards;
    $klientis->Epasts = $epasts;
    $klientis->TelefonaNumurs = $telefonaNumurs;
    $klientis->UznemumaNosaukums = $uznemumaNosaukums;
    $klientis->JuridiskaAdrese = $juridiskaAdrese;
    $klientis->RegistracijasNumurs = $registracijasNumurs;
    $klientis->KontaNumurs = $kontaNumurs;
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

    $dati->merge($this->normalizedClientData($dati));
    $dati->validate($this->clientValidationRules(), $this->clientValidationMessages());

    $vards = trim((string) $dati->input('Vards'));
    $uzvards = trim((string) $dati->input('Uzvards'));
    $epasts = trim((string) $dati->input('Epasts'));
    $telefonaNumurs = trim((string) $dati->input('TelefonaNumurs'));
    $uznemumaNosaukums = trim((string) $dati->input('UznemumaNosaukums'));
    $juridiskaAdrese = trim((string) $dati->input('JuridiskaAdrese'));
    $registracijasNumurs = trim((string) $dati->input('RegistracijasNumurs'));
    $kontaNumurs = trim((string) $dati->input('KontaNumurs'));

    $duplicateExists = Klienti::query()
      ->where('KlientaID', '!=', $id)
      ->where(function ($query) use ($epasts, $telefonaNumurs, $uznemumaNosaukums, $juridiskaAdrese, $registracijasNumurs, $kontaNumurs) {
        $query->whereRaw('LOWER(Epasts) = ?', [strtolower($epasts)])
          ->orWhere('TelefonaNumurs', $telefonaNumurs)
          ->orWhere('UznemumaNosaukums', $uznemumaNosaukums)
          ->orWhere('JuridiskaAdrese', $juridiskaAdrese)
          ->orWhere('RegistracijasNumurs', $registracijasNumurs)
          ->orWhere('KontaNumurs', $kontaNumurs);
      })
      ->exists();

    if ($duplicateExists) {
      return back()->withInput()->withErrors(['duplicate' => 'Klienta dati jau eksistē. Lūdzu, izmainiet laukus un mēģiniet vēlreiz.']);
    }

    // Atjauno klienta laukus pēc ID.
    DB::table('klienti')
      ->where('KlientaID', $id)
      ->update([
        'Vards' => $vards,
        'Uzvards' => $uzvards,
        'Epasts' => $epasts,
        'TelefonaNumurs' => $telefonaNumurs,
        'UznemumaNosaukums' => $uznemumaNosaukums,
        'JuridiskaAdrese' => $juridiskaAdrese,
        'RegistracijasNumurs' => $registracijasNumurs,
        'KontaNumurs' => $kontaNumurs,
      ]);

    return redirect()->to('/Klienti')->with('success', 'Ieraksts tika atjaunināts');
  }
}
