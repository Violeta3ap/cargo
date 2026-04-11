<?php

namespace App\Http\Controllers;

use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda vagona veidu sarakstu un CRUD darbības.
class VeidiController extends Controller
{
    private function normalizeNameValue(?string $value): string
    {
        return (string) preg_replace('/[^\p{L}]/u', '', trim((string) $value));
    }

    // Pārbauda vai lietotājam nav administratora tiesību.
    private function userCannotModify()
    {
        return !auth()->check() || !auth()->user()->isAdmin();
    }

    // Veidu saraksts ar meklēšanu un kārtošanu.
    public function showAllVeidi(Request $request)
    {
        // Filtrēšanas parametrs (precīza atbilstība)
        $search = trim((string) $request->query('search', ''));
        
        // Meklēšanas parametrs (LIKE atbilstība)
        $nosaukumsSearch = trim((string) $request->query('nosaukums_search', ''));

        $veidaOptions = Veidi::query()
            ->select('Nosaukums')
            ->distinct()
            ->orderBy('Nosaukums')
            ->pluck('Nosaukums');
        
        // Kārtošanas parametri
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
        
        // Pārbauda vai kārtošanas lauks ir atļauts
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'VeidaID';
        }
        
        // Pārbauda kārtošanas virzienu
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
        
        // Veidojam vaicājumu
        $query = Veidi::query();
        
        // Filtrēšana pēc Nosaukuma (precīza atbilstība)
        if ($search !== '') {
            $query->where('Nosaukums', $search);
        }
        
        // Meklēšana pēc Nosaukuma (LIKE atbilstība)
        if ($nosaukumsSearch !== '') {
            $query->where('Nosaukums', 'like', '%' . $nosaukumsSearch . '%');
        }
        
        // Pievienojam kārtošanu un izgūstam datus
        $dati = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(15)
            ->appends($request->query());
        
        return view('Veidi', compact('dati', 'sortBy', 'sortOrder', 'search', 'nosaukumsSearch', 'veidaOptions'));
    }

    // Dzēš veidu.
    public function delete($id)
    {
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst dzēst ierakstus.');
        }

        DB::table('veidi')->where('VeidaID', $id)->delete();
        return redirect('/Veidi')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu.
    public function create()
    {
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
        }

        return view('VeidiPiev');
    }

    // Parāda veida detaļas.
    public function details($id)
    {
        $veidi = Veidi::find($id);
        return view('VeidiApskate', ['veidi' => $veidi]);
    }

    // Saglabā jaunu veidu.


    
    public function DatuSubmit(Request $dati)
{
    // Ne-administratoriem liedz pievienot jaunus veidus.
    if ($this->userCannotModify()) {
        return redirect('/Veidi')->with('error', 'Tikai administrators drīkst pievienot ierakstus.');
    }

    $dati->merge([
        'Nosaukums' => $this->normalizeNameValue($dati->input('Nosaukums')),
    ]);

    // ✅ Validācija
    $dati->validate([
        'Nosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{L}+$/u'],
        'Celtspeja' => 'required|numeric|min:1',
        'VagonuSkaits' => 'required|integer|min:1',
        'CenaParDiennakti' => 'required|numeric|min:1',
    ], [
        'Nosaukums.required' => 'Lauks "Nosaukums" ir obligāts.',
        'Nosaukums.regex' => 'Laukā "Nosaukums" drīkst ievadīt tikai burtus.',
    ]);

    // Izveido jaunu vagona veida ierakstu.
    $veidi = new Veidi();
    $veidi->Nosaukums = $dati->input('Nosaukums');
    $veidi->Celtspeja = $dati->input('Celtspeja');
    $veidi->VagonuSkaits = $dati->input('VagonuSkaits');
    $veidi->CenaParDiennakti = $dati->input('CenaParDiennakti');
    $veidi->save();

    
    return redirect()->to('/Veidi')->with('success', 'Ieraksts tika pievienots');
}

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
        }

        $veidi = Veidi::find($id);
        return view('VeidiEdit', ['veidi' => $veidi]);
    }

    // Saglabā rediģētas vērtības.



    public function editSubmit(Request $dati, $id)
{
        // Ne-administratoriem liedz rediģēt veidu datus.
        if ($this->userCannotModify()) {
            return redirect('/Veidi')->with('error', 'Tikai administrators drīkst rediģēt ierakstus.');
    }

    $dati->merge([
        'Nosaukums' => $this->normalizeNameValue($dati->input('Nosaukums')),
    ]);

    // ✅ Validācija
    $dati->validate([
        'Nosaukums' => ['required', 'string', 'max:30', 'regex:/^\p{L}+$/u'],
        'Celtspeja' => 'required|numeric|min:1',
        'VagonuSkaits' => 'required|integer|min:1',
        'CenaParDiennakti' => 'required|numeric|min:1',
    ], [
        'Nosaukums.required' => 'Lauks "Nosaukums" ir obligāts.',
        'Nosaukums.regex' => 'Laukā "Nosaukums" drīkst ievadīt tikai burtus.',
    ]);

    // Atjauno vagona veida laukus pēc ID.
    DB::table('veidi')
        ->where('VeidaID', $id)
        ->update([
            'Nosaukums' => $dati->input('Nosaukums'),
            'Celtspeja' => $dati->input('Celtspeja'),
            'VagonuSkaits' => $dati->input('VagonuSkaits'),
            'CenaParDiennakti' => $dati->input('CenaParDiennakti'),
        ]);

    return redirect()->to('/Veidi')->with('success', 'Ieraksts tika atjaunināts');
}
    }
