<?php

namespace App\Http\Controllers; // Controlleru namespace

use Illuminate\Http\Request; // Formu datu saņemšanai
use App\Models\Veidi; // Veidu modelis
use Illuminate\Support\Facades\DB; // Datubāzes operācijas

class VeidiController extends Controller
{



    public function showAllVeidi() // Parāda visu veidu sarakstu
    {
        $veidi = new Veidi();
        return view('Veidi', ['dati' => $veidi->orderBy('VeidaID', 'asc')->get()]); // Nosūta datus uz skatu
    }




    public function delete($id) // Dzēš konkrētu veidu
    {
        DB::table('veidi')->where('VeidaID', $id)->delete(); // Dzēš ierakstu tabulā
        return redirect('/Veidi')->with('success', 'Ieraksts tika dzēsts'); // Pāradresē ar paziņojumu
    }




    public function create() // Sagatavo formu jaunam veidam
    {
        return view('VeidiPiev'); // Nosūta uz pievienošanas skatu
    }




    public function details($id) // Parāda konkrētā veida detaļas
    {
        $veidi = Veidi::find($id); // Atrod ierakstu pēc ID
        return view('VeidiApskate', ['veidi' => $veidi]); // Nosūta uz detaļu skatu
    }




    public function DatuSubmit(Request $dati) // Saglabā jaunu veidu
    {
        $veidi = new Veidi();
        $veidi->Nosaukums = $dati->input('Nosaukums');
        $veidi->Celtspeja = $dati->input('Celtspeja'); 
        
        $veidi->VagonuSkaits = $dati->input('VagonuSkaits');
        $veidi->CenaParDiennakti = $dati->input('CenaParDiennakti');// Iestata veida nosaukumu
        $veidi->save(); // Saglabā datubāzē

        return redirect()->to('/Veidi')->with('success', 'Ieraksts tika pievienots'); // Pāradresē uz sarakstu
    }

	



    public function edit($id) // Sagatavo rediģēšanas formu konkrētam veidam
    {
        $veidi = Veidi::find($id); // Atrod ierakstu pēc ID
        return view('VeidiEdit', ['veidi' => $veidi]); // Nosūta uz rediģēšanas skatu
    }




    public function editSubmit(Request $dati, $id) // Saglabā izmaiņas veidā
    {
        DB::table('veidi')
            ->where('VeidaID', $id)
            ->update([
                'Nosaukums' => $dati->input('Nosaukums'), // Atjaunina veida nosaukumu
                'Celtspeja' => $dati->input('Celtspeja'),
                'VagonuSkaits' => $dati->input('VagonuSkaits'),
                'CenaParDiennakti' => $dati->input('CenaParDiennakti'),
            ]);

        return redirect()->to('/Veidi')->with('success', 'Ieraksts tika atjaunināts'); // Pāradresē uz sarakstu
    }



    
}