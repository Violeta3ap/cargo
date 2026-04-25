<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klienti;
use Illuminate\Support\Facades\DB;

/**
 * KlientiController - Pārvalda klientu ierakstu sarakstu un CRUD darbības
 * 
 * Šis kontrolieris atļauj administratoriem pārvaldīt klientu datus:
 * - Apskatīt klientu sarakstu ar filtrēšanu, meklēšanu un kārtošanu
 * - Pievienot jaunus klientus
 * - Rediģēt klienta informāciju
 * - Dzēst klientus (tikai ja tiem nav nomas)
 * - Validēt un normalizēt ievades datus
 */
class KlientiController extends Controller
{
  /**
   * Pārbauda vai lietotājs ir autentificēts un ir administrators
   * 
   * @return null|Illuminate\Http\RedirectResponse
   */
  private function requireAdminAccess()
  {
    // Pārbauda autentifikāciju un administratora statusu
    if (!auth()->check() || !auth()->user()->isAdmin()) {
      return redirect('/')->with('error', 'Piekļuve atļauta tikai administratoram.');
    }

    return null;
  }

  private function requireStaffAccess()
  {
    if (!auth()->check() || !auth()->user()->isStaff()) {
      return redirect('/')->with('error', 'Piekļuve atļauta tikai administratoram vai darbiniekam.');
    }

    return null;
  }

  /**
   * Normalizē burti-tikai vērtības - noņem ciparus un speciālos simbolus
   * Rezultāts: Teksts ar lielajiem sākumburtiiem
   * Piemērs: "jānis NOVADS" → "Jānis Novads"
   * 
   * @param string|null $value - Ievades vērtība
   * @return string - Normalizēta vērtība
   */
  private function normalizeLettersOnlyValue(?string $value): string
  {
    // Noņem visus simbolus, izņemot burtus (\p{L}) un atstarpes (\s)
    $value = (string) preg_replace('/[^\p{L}\s]/u', '', trim((string) $value));
    // Aizstāj vairākas pēc kārtas esošas atstarpes ar vienu
    $value = (string) preg_replace('/\s+/u', ' ', $value);

    // Ja risultāts ir tukšs, atgriež tukšu virkni
    if ($value === '') {
      return '';
    }

    // Konvertē uz Title Case formātu (katrs vārds sākas ar lielajiem burtiem)
    return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
  }

  /**
   * Normalizē adreses vērtības - pieņem ciparus, burtus, atstarpes, punktus, pēdiņas, domuzīmes
   * Pirmais simbols vienmēr ir lielais burts
   * Piemērs: "riga 1234" → "Riga 1234"
   * 
   * @param string|null $value - Ievades vērtība
   * @return string - Normalizēta adreses vērtība
   */
  private function normalizeAddressValue(?string $value): string
  {
    // Noņem visus simbolus, izņemot ciparus, burtus, atstarpes, punktus, komatus, mīnusus un slīpsvītre
    $value = (string) preg_replace('/[^0-9\p{L}\s\.,\-\/]/u', '', trim((string) $value));
    // Aizstāj vairākas atstarpes ar vienu
    $value = (string) preg_replace('/\s+/u', ' ', $value);

    // Ja rezultāts ir tukšs, atgriež to
    if ($value === '') {
      return '';
    }

    // Pirmais burts uz lielajiem, pārējie paliek nemainīti
    return mb_strtoupper(mb_substr($value, 0, 1, 'UTF-8'), 'UTF-8')
      . mb_substr($value, 1, null, 'UTF-8');
  }

  /**
   * Normalizē konta numurus - noņem speciālos simbolus un nodrošina "LV" prefiksu
   * Maksimālais garums 21 simbols (IBAN standarts)
   * Piemērs: "lv92-1019-0503-0100-0000" → "LV921019050301000000"
   * 
   * @param string|null $value - Ievades vērtība
   * @return string - Normalizēts konta numurs
   */
  private function normalizeAccountNumber(?string $value): string
  {
    // Noņem visus simbolus, izņemot burtus A-Z un ciparus, pēc tam konvertē uz LIELAJIEM
    $cleaned = strtoupper((string) preg_replace('/[^A-Z0-9]/i', '', trim((string) $value)));

    // Ja rezultāts ir tukšs, atgriež to
    if ($cleaned === '') {
      return '';
    }

    // Ja jau sākas ar "LV", paņem tikai pirmos 21 simbolus
    if (str_starts_with($cleaned, 'LV')) {
      return substr($cleaned, 0, 21);
    }

    // Ja nesākas ar "LV", pievieno prefiksu un paņem 21 simbolu
    return substr('LV' . $cleaned, 0, 21);
  }

  /**
   * Normalizē visus klienta ievadītos datus
   * Lietošana: $klienti->normalizedClientData($request)
   * 
   * @param Illuminate\Http\Request $dati - Formas ievades dati
   * @return array - Masīvs ar normalizētiem datiem
   */
  private function normalizedClientData(Request $dati): array
  {
    return [
      'Vards' => $this->normalizeLettersOnlyValue($dati->input('Vards')),
      'Uzvards' => $this->normalizeLettersOnlyValue($dati->input('Uzvards')),
      'Epasts' => strtolower(trim((string) $dati->input('Epasts'))),
      // Noņem visus ne-ciparus no telefona numura
      'TelefonaNumurs' => (string) preg_replace('/\D+/', '', (string) $dati->input('TelefonaNumurs')),
      'UznemumaNosaukums' => $this->normalizeLettersOnlyValue($dati->input('UznemumaNosaukums')),
      'JuridiskaAdrese' => $this->normalizeAddressValue($dati->input('JuridiskaAdrese')),
      // Noņem ne-ciparus no reģistrācijas numura, takes max 11 ciparus
      'RegistracijasNumurs' => substr((string) preg_replace('/\D+/', '', (string) $dati->input('RegistracijasNumurs')), 0, 11),
      'KontaNumurs' => $this->normalizeAccountNumber($dati->input('KontaNumurs')),
    ];
  }

  /**
   * Nosaka validācijas noteikumus klienta datiem
   * Validācija tiek veikta automātiski, izmantojot Laravel validatoru
   * 
   * @return array - Masīvs ar validācijas noteikumiem
   */
  private function clientValidationRules(): array
  {
    return [
      // Vārds: obligāts, maksimāli 30 simboli, sākas ar lielajiem burtiem
      'Vards' => ['required', 'string', 'max:30', 'regex:/^\p{Lu}[\p{L}\s]*$/u'],
      // Uzvārds: obligāts, maksimāli 30 simboli, sākas ar lielajiem burtiem
      'Uzvards' => ['required', 'string', 'max:30', 'regex:/^\p{Lu}[\p{L}\s]*$/u'],
      // E-pasts: obligāts, derīgs e-pasta formāts, maksimāli 255 simboli
      'Epasts' => ['required', 'email', 'max:255'],
      // Telefona numurs: obligāts, tieši 8 cipari
      'TelefonaNumurs' => ['required', 'digits:8'],
      // Uzņēmuma nosaukums: obligāts, maksimāli 30 simboli, sākas ar lielajiem burtiem
      'UznemumaNosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{Lu}[\p{L}\s]*$/u'],
      // Juridiskā adrese: obligāta, maksimāli 255 simboli, sākas ar lielajiem burtiem
      'JuridiskaAdrese' => ['required', 'string', 'max:255', 'regex:/^\p{Lu}[0-9\p{L}\s\.,\-\/]*$/u'],
      // Reģistrācijas numurs: obligāts, 1-11 cipari
      'RegistracijasNumurs' => ['required', 'digits_between:1,11'],
      // Konta numurs: obligāts, maksimāli 21 simbols, sākas ar "LV"
      'KontaNumurs' => ['required', 'string', 'max:21', 'regex:/^LV[A-Z0-9]*$/'],
    ];
  }

  /**
   * Nosaka kļūdu ziņojumus validācijas kļūdām
   * Šie ziņojumi tiek rādīti lietotājam, ja validācija neizdodas
   * 
   * @return array - Masīvs ar validācijas kļūdu ziņojumiem
   */
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
      'RegistracijasNumurs.digits_between' => 'Laukā "Reģistrācijas numurs" jāievada no 1 līdz 11 cipariem.',
      'KontaNumurs.required' => 'Lauks "Konta numurs" ir obligāts.',
      'KontaNumurs.max' => 'Laukam "Konta numurs" maksimālais garums ir 21 simbols.',
      'KontaNumurs.regex' => 'Laukam "Konta numurs" jāsākas ar "LV", un tālāk drīkst būt tikai burti vai cipari.',
    ];
  }

  /**
   * Attēlo klientu sarakstu ar filtrēšanu, meklēšanu un kārtošanu
   * 
   * @param Illuminate\Http\Request $request - Pieprasījums ar query parametriem
   * @return Illuminate\View\View - Skats ar klientu sarakstu
   */
  public function showAllKlienti(Request $request)
  {
    // Pārbauda administratora vai darbinieka tiesības
    if ($response = $this->requireStaffAccess()) {
      return $response;
    }

    // Nolasa filtrēšanas parametrus no URL (piem.: ?vards=Jānis)
    $vards = trim((string) $request->query('vards', ''));
    $uzvards = trim((string) $request->query('uzvards', ''));
    $uznemumanos = trim((string) $request->query('uznemumanos', ''));
    // Universāla meklēšana visos laukos
    $search = trim((string) $request->query('search', ''));
    
    // Kārtošanas parametri - Par kuriem laukiem un kārtībā
    $sortBy = $request->query('sort_by', 'KlientaID');
    $sortOrder = $request->query('sort_order', 'asc');
    
    // Atļauto kārtošanas lauku saraksts (drošībai - SQL injection novēršanai)
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
    
    // Pārbauda vai kārtošanas lauks ir draudzīgs - ja nē, izmanto noklusēto
    if (!in_array($sortBy, $allowedSortFields)) {
      $sortBy = 'KlientaID';
    }
    
    // Pārbauda kārtošanas virzienu (tikai "asc" vai "desc")
    if (!in_array($sortOrder, ['asc', 'desc'])) {
      $sortOrder = 'asc';
    }

    // Sāk veidot vaicājumu
    $query = Klienti::query();

    // Pievieno filtrus tikai, ja tie nav tukši (aktīvā filtrēšana)
    if ($vards !== '') {
      $query->where('Vards', 'like', '%' . $vards . '%');
    }

    if ($uzvards !== '') {
      $query->where('Uzvards', 'like', '%' . $uzvards . '%');
    }

    if ($uznemumanos !== '') {
      $query->where('UznemumaNosaukums', 'like', '%' . $uznemumanos . '%');
    }

    // Universāla meklēšana - meklē visos galvenajos laukos
    if ($search !== '') {
      $query->where(function ($builder) use ($search) {
        $builder->where('Vards', 'like', '%' . $search . '%')
          ->orWhere('Uzvards', 'like', '%' . $search . '%')
          ->orWhere('UznemumaNosaukums', 'like', '%' . $search . '%');
      });
    }

    // Izpilda vaicājumu ar kārtošanu un pagināciju (15 ieraksti uz lapas)
    $klientis = $query
      ->orderBy($sortBy, $sortOrder)
      ->paginate(15)
      ->appends($request->query()); // Pievieno filtru parametrus paginācijas linkos

    // Atgriež skatu ar datiem
    return view('Klienti', compact('klientis', 'vards', 'uzvards', 'uznemumanos', 'search', 'sortBy', 'sortOrder'));
  }

  /**
   * Dzēš klienta ierakstu (tikai if klientam nav aktīvu nomu)
   * 
   * @param int $id - Klienta ID dzēšanai
   * @return Illuminate\Http\RedirectResponse
   */
  public function delete($id)
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Pārbauda vai klientam ir kādas aktīvas nomas
    $hasRentals = DB::table('vagonunoma')->where('KlientaID', $id)->exists();
    if ($hasRentals) {
      // Klientu ar nomām dzēst nedrīkst
      return redirect('/Klienti')->with('error', 'Klientu ar izveidotu nomu dzēst nedrīkst.');
    }

    // Dzēš klienta ierakstu no datu bāzes
    DB::table('klienti')->where('KlientaID', $id)->delete();
    return redirect('/Klienti')->with('success', 'Ieraksts tika dzēsts');
  }

  /**
   * Atver pievienošanas formu jaunam klientam
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
    return view('KlientuPiev');
  }

  /**
   * Saglabā jaunu klienta ierakstu datu bāzē
   * Validē un normalizē visus ievadītos datus
   * 
   * @param Illuminate\Http\Request $dati - Formas ievades dati
   * @return Illuminate\Http\RedirectResponse
   */
  public function KlientiSubmit(Request $dati)
  {
    // Pārbauda administratora tiesības
    if ($response = $this->requireAdminAccess()) {
      return $response;
    }

    // Normalizē ikvienus ievadītos datus (burtu lielums, speciālie simboli utt.)
    $dati->merge($this->normalizedClientData($dati));
    
    // Validē datus saskaņā ar noteiktajiem noteikumiem
    $dati->validate($this->clientValidationRules(), $this->clientValidationMessages());

    // Nolasa validētos un normalizētos datus
    $vards = trim((string) $dati->input('Vards'));
    $uzvards = trim((string) $dati->input('Uzvards'));
    $epasts = trim((string) $dati->input('Epasts'));
    $telefonaNumurs = trim((string) $dati->input('TelefonaNumurs'));
    $uznemumaNosaukums = trim((string) $dati->input('UznemumaNosaukums'));
    $juridiskaAdrese = trim((string) $dati->input('JuridiskaAdrese'));
    $registracijasNumurs = trim((string) $dati->input('RegistracijasNumurs'));
    // Dīna daļa ir saīsināta, rīcības turpinās ar konta numura nolasīšanu un datu saglabāšanu
  }
}
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
