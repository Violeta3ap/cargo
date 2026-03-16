<?php

namespace App\Http\Controllers; // Controlleru namespace

use Illuminate\Http\Request; // Formu datu saņemšanai
use App\Models\VagonaRaksturojums; // Vagona raksturojumu modelis
use Illuminate\Support\Facades\DB; // Datubāzes operācijas

use App\Models\Veidi; // Veidu modelis
use App\Models\Kravas; // Kravas modelis

class VagonaRaksturojumsController extends Controller
{
    public function showAllVagonaRaksturojums() // Parāda visu vagona raksturojumu sarakstu
    {
        $dati = new VagonaRaksturojums();
        return view('VagonaRaksturojums', ['dati' => $dati->orderBy('VagonaID', 'asc')->get()]); // Nosūta datus uz skatu
    }

    public function delete($id) // Dzēš konkrētu vagona raksturojumu
    {
        DB::table('vagonaraksturojums')->where('VagonaID', $id)->delete(); // Dzēš ierakstu tabulā
        return redirect('/VagonaRaksturojums')->with('success', 'Ieraksts tika dzēsts'); // Atpakaļ ar paziņojumu
    }

    public function create() // Sagatavo formu jaunam vagona raksturojumam
    {
        return view('VagonaRaksturojumaPiev'); // Nosūta uz pievienošanas skatu
    }

    public function details($id) // Parāda konkrētā vagona raksturojuma detaļas
    {
        $raksturojums = VagonaRaksturojums::find($id); // Atrod ierakstu pēc ID
        return view('VagonaRaksturojumaApskate', ['vagonaraksturojums' => $raksturojums]); // Nosūta uz detaļu skatu
    }

    public function DatuSubmit(Request $dati) // Saglabā jaunu vagona raksturojumu
    {
        $raksturojums = new VagonaRaksturojums();
        $raksturojums->VeidaID = $dati->input('VeidaID'); // Iestata veida ID
        $raksturojums->KravasID = $dati->input('KravasID'); // Iestata kravas ID
        $raksturojums->Celtspeja = $dati->input('Celtspeja'); // Iestata celtspēju
        $raksturojums->VagonaNumurs = $dati->input('VagonaNumurs'); // Iestata vagona numuru
        $raksturojums->save(); // Saglabā datubāzē

        return redirect()->to('/VagonaRaksturojums')->with('success', 'Ieraksts tika pievienots'); // Pāradresē uz sarakstu
    }

    public function edit($id) // Sagatavo rediģēšanas formu konkrētam vagona raksturojumam
    {
        $raksturojums = VagonaRaksturojums::find($id); // Atrod ierakstu pēc ID
        return view('VagonaRaksturojumaEdit', ['vagonaraksturojums' => $raksturojums]); // Nosūta uz rediģēšanas skatu
    }

    public function editSubmit(Request $dati, $id) // Saglabā izmaiņas vagona raksturojumā
    {
        DB::table('vagonaraksturojums')
            ->where('VagonaID', $id)
            ->update([
                'VeidaID' => $dati->input('VeidaID'), // Atjaunina veida ID
                'KravasID' => $dati->input('KravasID'), // Atjaunina kravas ID
                'Celtspeja' => $dati->input('Celtspeja'), // Atjaunina celtspēju
                'VagonaNumurs' => $dati->input('VagonaNumurs'), // Atjaunina vagona numuru
            ]);

        return redirect()->to('/VagonaRaksturojums')->with('success', 'Ieraksts tika atjaunināts'); // Pāradresē uz sarakstu
    }
}