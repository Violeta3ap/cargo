<?php

namespace App\Http\Controllers; // Controlleru namespace

use Illuminate\Http\Request; // Formu datu saņemšanai
use App\Models\VagonuDati; // Vagonu datu modelis
use Illuminate\Support\Facades\DB; // Datubāzes operācijas

class VagonuDatiController extends Controller
{



    public function showAllVagonuDati() // Parāda visu vagonu datu sarakstu
    {
        $dati = new VagonuDati();
        return view('VagonuDati', ['dati' => $dati->orderBy('DatuID', 'asc')->get()]); // Nosūta datus uz skatu
    }




    public function delete($id) // Dzēš konkrētu vagonu datu ierakstu
    {
        DB::table('vagonudati')->where('DatuID', $id)->delete(); // Dzēš ierakstu tabulā
        return redirect('/VagonuDati')->with('success', 'Ieraksts tika dzēsts'); // Atpakaļ ar paziņojumu
    }




    public function create() // Sagatavo formu jaunam vagonu datu ierakstam
    {
        return view('VagonuDatuPiev'); // Nosūta uz pievienošanas skatu
    }




    public function details($id) // Parāda konkrētā vagonu datu ieraksta detaļas
    {
        $datu = VagonuDati::find($id); // Atrod ierakstu pēc ID
        return view('VagonuDatuApskate', ['vagonudati' => $datu]); // Nosūta uz detaļu skatu
    }




    public function DatuSubmit(Request $dati) // Saglabā jaunu vagonu datu ierakstu
    {
        $datu = new VagonuDati();
        $datu->NomasID = $dati->input('NomasID'); // Iestata nomas ID
        $datu->VagonaID = $dati->input('VagonaID'); // Iestata vagona ID
        $datu->save(); // Saglabā datubāzē

        return redirect()->to('/VagonuDati')->with('success', 'Ieraksts tika pievienots'); // Pāradresē uz sarakstu
    }




    public function edit($id) // Sagatavo rediģēšanas formu konkrētam vagonu datu ierakstam
    {
        $datu = VagonuDati::find($id); // Atrod ierakstu pēc ID
        return view('VagonuDatuEdit', ['vagonudati' => $datu]); // Nosūta uz rediģēšanas skatu
    }




    public function editSubmit(Request $dati, $id) // Saglabā izmaiņas vagonu datu ierakstā
    {
        DB::table('vagonudati')
            ->where('DatuID', $id)
            ->update([
                'NomasID' => $dati->input('NomasID'), // Atjaunina nomas ID
                'VagonaID' => $dati->input('VagonaID'), // Atjaunina vagona ID
            ]);

        return redirect()->to('/VagonuDati')->with('success', 'Ieraksts tika atjaunināts'); // Pāradresē uz sarakstu
    }


    
}