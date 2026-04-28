<?php

namespace App\Http\Controllers;

use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * VeidiController - Pārvalda vagona veidu (vagonu klasifikācijas) sarakstu un CRUD darbības
 * 
 * Šis kontrolieris atļauj administratoriem pārvaldīt vagonu veidus:
 * - Apskatīt vagonu veidu sarakstu ar filtrēšanu, meklēšanu un kārtošanu
 * - Pievienot jaunus vagonu veidus
 * - Rediģēt vagonu veidu informāciju
 * - Dzēst vagonu veidus
 * 
 * Katrs vagona veids satur:
 * - Nosaukums (piemēram: "Jumta vagons")
 * - Celtspēja (tonnas)
 * - VagonuSkaits (kopējais skaits nolietojumā)
 * - CenaParDiennakti (nomas cena dienā)
 */
class VeidiController extends Controller
{
    /**
     * Normalizē vagona veida nosaukuma vērtības - noņem ciparus un speciālos simbolus
     * Rezultāts: Tikai burti
     * 
     * @param string|null $value - Ievades vērtība
     * @return string - Normalizēta vērtība
     */
    private function normalizeNameValue(?string $value): string
    {
        // Noņem visus simbolus, izņemot burtus (\p{L})
        return (string) preg_replace('/[^\p{L}]/u', '', trim((string) $value));
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
     * Attēlo vagonu veidu sarakstu ar filtrēšanu, meklēšanu un kārtošanu
     * 
     * @param Illuminate\Http\Request $request - Pieprasījums ar query parametriem
     * @return Illuminate\View\View - Skats ar vagonu veidu sarakstu
     */
    public function showAllVeidi(Request $request)
    {
        // Filtrēšanas parametrs - precīza atbilstība pēc Nosaukuma
        $search = trim((string) $request->query('search', ''));
        
        // Meklēšanas parametrs - LIKE atbilstība (daļēja sakritība)
        $nosaukumsSearch = trim((string) $request->query('nosaukums_search', ''));

        // Iegūst visus unikālos vagonu veidu nosaukumus (for dropdown filter)
        $veidaOptions = Veidi::query()
            ->select('Nosaukums')
            ->distinct()
            ->orderBy('Nosaukums')
            ->pluck('Nosaukums');
        
        // Kārtošanas parametri - Par kuriem laukiem un kārtībā
        $sortBy = $request->query('sort_by', 'VeidaID');
        $sortOrder = $request->query('sort_order', 'asc');
        
        // Atļauto kārtošanas lauku saraksts (drošībai)
        $allowedSortFields = [
            'Nosaukums',
            'Celtspeja',
            'VagonuSkaits',
            'CenaParDiennakti',
            'VeidaID'
        ];
        
        // Pārbauda vai kārtošanas lauks ir draudzīgs
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'VeidaID';
        }
        
        // Pārbauda kārtošanas virzienu
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
        
        // Sāk veidot vaicājumu
        $query = Veidi::query();
        
        // Filtrēšana pēc Nosaukuma (precīza atbilstība)
        if ($search !== '') {
            $query->where('Nosaukums', $search);
        }
        
        // Meklēšana pēc Nosaukuma (daļēja atbilstība - LIKE)
        if ($nosaukumsSearch !== '') {
            $query->where('Nosaukums', 'like', '%' . $nosaukumsSearch . '%');
        }
        
        // Izpilda vaicājumu ar kārtošanu un pagināciju (15 ieraksti uz lapas)
        $dati = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(15)
            ->appends($request->query()); // Pievieno filtru parametrus paginācijas linkos
        
        // Atgriež skatu ar datiem
        return view('Veidi', compact('dati', 'sortBy', 'sortOrder', 'search', 'nosaukumsSearch', 'veidaOptions'));
    }

    /**
     * Dzēš vagona veidu pēc tā ID
     * 
     * @param int $id - Vagona veida ID dzēšanai
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        // Pārbauda vai lietotājs var pieņemt izmaiņas
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst dzēst ierakstus.');
        }

        // Dzēš vagona veidu no tabulas "veidi" pēc VeidaID
        DB::table('veidi')->where('VeidaID', $id)->delete();
        // Novirza uz vagonu veidu sarakstu ar apstiprinājuma ziņojumu
        return redirect('/Veidi')->with('success', 'Ieraksts tika dzēsts');
    }

    /**
     * Atver pievienošanas formu jaunam vagona veidam
     * 
     * @return Illuminate\View\View
     */
    public function create()
    {
        // Pārbauda vai lietotājs var pieņemt izmaiņas
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
        }

        // Atgriež skatu ar pievienošanas formu
        return view('VeidiPiev');
    }

    /**
     * Saglabā jaunu vagona veidu datus no formas
     * Validē visus ievadītos datus
     * 
     * @param Illuminate\Http\Request $dati - Formas dati
     * @return Illuminate\Http\RedirectResponse
     */
    public function DatuSubmit(Request $dati)
    {
        // Pārbauda vai lietotājs var pieņemt izmaiņas
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
        }

        // Normalizē vagona veida nosaukumu (noņem speciālos simbolus)
        $dati->merge([
            'Nosaukums' => $this->normalizeNameValue($dati->input('Nosaukums')),
        ]);

        // Validē formas datus
        $dati->validate([
            'Nosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{L}+$/u'],
            'Celtspeja' => 'required|numeric|min:1',
            'VagonuSkaits' => 'required|integer|min:1',
            'CenaParDiennakti' => 'required|numeric|min:1',
        ], [
            'Nosaukums.required' => 'Lauks "Nosaukums" ir obligāts.',
            'Nosaukums.regex' => 'Laukā "Nosaukums" drīkst ievadīt tikai burtus.',
        ]);

        // Izveido jaunu vagona veida ierakstu
        $veidi = new Veidi();
        $veidi->Nosaukums = $dati->input('Nosaukums');
        $veidi->Celtspeja = $dati->input('Celtspeja');
        $veidi->VagonuSkaits = $dati->input('VagonuSkaits');
        $veidi->CenaParDiennakti = $dati->input('CenaParDiennakti');
        // Saglabā jauno ierakstu datu bāzē
        try {
            $veidi->save();
        } catch (\Illuminate\Database\QueryException $e) {
            // Pārbauda vai kļūda ir saistīta ar datu garumu
            if (str_contains($e->getMessage(), 'Data too long for column')) {
                return back()->withInput()->withErrors(['database' => 'Ievadītie dati ir pārāk gari kādam no laukiem. Lūdzu, pārbaudiet un saīsiniet tekstu.']);
            }
            // Citas datu bāzes kļūdas
            return back()->withInput()->withErrors(['database' => 'Datu bāzes kļūda: ' . $e->getMessage()]);
        }

        // Novirza uz vagonu veidu sarakstu ar apstiprinājuma ziņojumu
        return redirect()->to('/Veidi')->with('success', 'Ieraksts tika pievienots');
    }

    /**
     * Atver rediģēšanas formu esošam vagona veidam
     * 
     * @param int $id - Vagona veida ID, ko rediģēt
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        // Pārbauda vai lietotājs var pieņemt izmaiņas
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
        }

        // Meklē vagona veidu pēc ID
        $veidi = Veidi::find($id);
        // Atgriež rediģēšanas skatu ar vagona veida datiem
        return view('VeidiEdit', ['veidi' => $veidi]);
    }

    /**
     * Atjaunina vagona veida datus datu bāzē
     * Validē visus ievadītos datus pirms atjaunināšanas
     * 
     * @param Illuminate\Http\Request $dati - Formas dati
     * @param int $id - Vagona veida ID, ko atjaunināt
     * @return Illuminate\Http\RedirectResponse
     */
    public function editSubmit(Request $dati, $id)
    {
        // Pārbauda vai lietotājs var pieņemt izmaiņas
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
        }

        // Normalizē vagona veida nosaukumu
        $dati->merge([
            'Nosaukums' => $this->normalizeNameValue($dati->input('Nosaukums')),
        ]);

        // Validē formas datus
        $dati->validate([
            'Nosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{L}+$/u'],
            'Celtspeja' => 'required|numeric|min:1',
            'VagonuSkaits' => 'required|integer|min:1',
            'CenaParDiennakti' => 'required|numeric|min:1',
        ], [
            'Nosaukums.required' => 'Lauks "Nosaukums" ir obligāts.',
            'Nosaukums.regex' => 'Laukā "Nosaukums" drīkst ievadīt tikai burtus.',
        ]);

        // Atjaunina vagona veida laukus datu bāzē pēc ID
        try {
            DB::table('veidi')
                ->where('VeidaID', $id)
                ->update([
                    'Nosaukums' => $dati->input('Nosaukums'),
                    'Celtspeja' => $dati->input('Celtspeja'),
                    'VagonuSkaits' => $dati->input('VagonuSkaits'),
                    'CenaParDiennakti' => $dati->input('CenaParDiennakti'),
                ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Pārbauda vai kļūda ir saistīta ar datu garumu
            if (str_contains($e->getMessage(), 'Data too long for column')) {
                return back()->withInput()->withErrors(['database' => 'Ievadītie dati ir pārāk gari kādam no laukiem. Lūdzu, pārbaudiet un saīsiniet tekstu.']);
            }
            // Citas datu bāzes kļūdas
            return back()->withInput()->withErrors(['database' => 'Datu bāzes kļūda: ' . $e->getMessage()]);
        }

        // Novirza uz vagonu veidu sarakstu ar apstiprinājuma ziņojumu
        return redirect()->to('/Veidi')->with('success', 'Ieraksts tika atjaunināts');
    }
}
