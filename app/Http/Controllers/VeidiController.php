<?php

namespace App\Http\Controllers;

use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda vagona veidu sarakstu un CRUD darbības.
class VeidiController extends Controller
{
    // Pārbauda vai lietotājam nav administratora tiesību.
    private function userCannotModify()
    {
        return !auth()->check() || !auth()->user()->isAdmin();
    }

    // Veidu saraksts ar meklēšanu un kārtošanu.
    public function showAllVeidi(Request $request)
    {
        // Meklēšanas parametrs
        $search = trim((string) $request->query('search', ''));

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
        
        // Meklēšana pēc nosaukuma
        if ($search !== '') {
            $query->where('Nosaukums', $search);
        }
        
        // Pievienojam kārtošanu un izgūstam datus
        $dati = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(5)
            ->appends($request->query());
        
        return view('Veidi', compact('dati', 'sortBy', 'sortOrder', 'search', 'veidaOptions'));
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

    // ✅ Validācija
    $dati->validate([
        'Nosaukums' => 'required|string|max:255',
        'Celtspeja' => 'required|numeric|min:1',
        'VagonuSkaits' => 'required|integer|min:1',
        'CenaParDiennakti' => 'required|numeric|min:1',
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

    // ✅ Validācija
    $dati->validate([
        'Celtspeja' => 'required|numeric|min:1',
        'VagonuSkaits' => 'required|integer|min:1',
        'CenaParDiennakti' => 'required|numeric|min:1',
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
