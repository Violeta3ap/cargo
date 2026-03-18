<?php

namespace App\Http\Controllers; // Controlleru namespace

use Illuminate\Http\Request; // Formu datu saņemšanai
use App\Models\Noma; // Noma modelis
use Illuminate\Support\Facades\DB; // Datubāzes operācijas
use App\Models\Klienti; // Klienti modelis
use App\Models\Darbinieki; // Darbinieki modelis
use App\Models\Kravas; // Kravas modelis
use App\Models\Veidi; // veidu modelis

class NomaController extends Controller
{




    public function showAllNoma() // Parāda visas nomas ierakstus
    {
        $noma = new Noma();
        return view('Noma', ['noma' => $noma->orderBy('NomasID', 'asc')->get()]); // Nosūta uz skatu ar visām nomām
    }




    public function delete($id) // Dzēš konkrētu nomu pēc ID
    {
        DB::table('vagonunoma')->where('NomasID', $id)->delete(); // Dzēš no tabulas
        return redirect('/Noma')->with('success', 'Ieraksts tika dzēsts'); // Pāradresē un rāda ziņu
    }




    public function create() // Sagatavo formu jaunai nomai
    {
        $klienti = Klienti::all();        // Ņem visus klientus
        $darbinieki = Darbinieki::all();  // Ņem visus darbiniekus
        $kravas = Kravas::all();           // Ņem visas kravas
        $veidi = Veidi::all();             // Ņem visus vagona veidus

        return view('NomaPiev', compact('klienti','darbinieki','kravas','veidi')); // Nosūta datus uz formu
    }




    public function details($id) // Parāda konkrētas nomas detaļas
    {
        $noma = Noma::find($id); // Atrod nomu pēc ID
        return view('NomaApskate', ['noma' => $noma]); // Nosūta uz detaļu skatu
    }




    public function NomaSubmit(Request $dati) // Saglabā jaunu nomu datubāzē
    {
        $noma = new Noma();
        $noma->KlientaID = $dati->input('KlientaID'); 
        $noma->DarbiniekaID = $dati->input('DarbiniekaID');
        $noma->KravasID = $dati->input('KravasID');
        $noma->Svars = $dati->input('Svars');
        $noma->VeidaID = $dati->input('VeidaID');
        $noma->Skaits = $dati->input('Skaits');
        $noma->NomasSakumaPeriods = $dati->input('NomasSakumaPeriods');
        $noma->NomasBeiguPeriods = $dati->input('NomasBeiguPeriods');
        $noma->NosutisanasStacija = $dati->input('NosutisanasStacija');
        $noma->Galastacija = $dati->input('Galastacija');
        $noma->KopejaMaksa = $dati->input('KopejaMaksa'); // Saglabā kopējo maksu
        $noma->save(); // Saglabā datubāzē

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika pievienots'); // Pāradresē uz sarakstu
    }




    public function edit($id) // Sagatavo nomas rediģēšanas formu
    {
        $noma = Noma::find($id); // Atrod nomu pēc ID
        $klienti = Klienti::all(); // Ņem visus klientus
        $darbinieki = Darbinieki::all(); // Ņem visus darbiniekus
        $kravas = Kravas::all(); // Ņem visas kravas
        $veidi = Veidi::all(); // Ņem visus vagona veidus

        return view('NomaEdit', compact('noma','klienti','darbinieki','kravas','veidi')); // Nosūta uz rediģēšanas skatu
    }




    public function editSubmit(Request $dati, $id) // Saglabā izmaiņas nomā
    {
        DB::table('vagonunoma')
            ->where('NomasID', $id)
            ->update([
                'KlientaID' => $dati->input('KlientaID'),
                'DarbiniekaID' => $dati->input('DarbiniekaID'),
                'KravasID' => $dati->input('KravasID'),
                'Svars' => $dati->input('Svars'),
                'VeidaID' => $dati->input('VeidaID'),
                'Skaits' => $dati->input('Skaits'),
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
                'NosutisanasStacija' => $dati->input('NosutisanasStacija'),
                'Galastacija' => $dati->input('Galastacija'),
                'KopejaMaksa' => $dati->input('KopejaMaksa'),
            ]); // Atjaunina datus tabulā

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika atjaunināts'); // Pāradresē uz sarakstu
    }



    
}