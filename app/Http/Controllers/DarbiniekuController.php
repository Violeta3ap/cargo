<?php

namespace App\Http\Controllers; // Norāda controller mapes vietu Laravel projektā

use Illuminate\Http\Request; // Klase formu datu saņemšanai
use App\Models\Darbinieki; // Modelis darbam ar darbinieku tabulu
use Illuminate\Support\Facades\DB; // Ļauj izmantot DB komandas
use App\Models\Amati; // Modelis darbam ar amatu tabulu

class DarbiniekuController extends Controller // Izveido controller klasi
{


    public function showAllDarbinieki() // Parāda visus darbiniekus
    {
     $darbiniekis= new Darbinieki(); // Izveido Darbinieki objekta instanci
     //dd($data->all()); // Debug funkcija datu pārbaudei
       return view('Darbinieki', ['darbiniekis' => $darbiniekis->orderBy('DarbiniekaID', 'asc')->get()]);
       // Atver Darbinieki lapu un nosūta sakārtotu darbinieku sarakstu
    }



    public function delete($id) // Funkcija darbinieka dzēšanai
    {
      DB::table('darbinieki')->where('DarbiniekaID', $id)->delete(); 
      // Dzēš darbinieku no datubāzes pēc ID

      return redirect('/Darbinieki')->with('success', 'Ieraksts tika dzēsts');
      // Pāradresē uz darbinieku sarakstu ar paziņojumu
    }



  public function create() // Atver darbinieka pievienošanas formu
{
    $amati = Amati::orderBy('AmataID','asc')->get(); 
    // Iegūst visus amatus no datubāzes

    return view('DarbiniekuPiev', ['amati' => $amati]); 
    // Atver pievienošanas lapu un nosūta amatu sarakstu
}



    public function details($id) // Parāda viena darbinieka informāciju
    {
      $darbiniekis = Darbinieki::find($id); // Atrod darbinieku pēc ID
      return view('DarbiniekiApskate', ['darbinieki' => $darbiniekis]);
      // Atver apskates lapu un nosūta darbinieka datus
    }



    public function DarbiniekiSubmit(Request $dati) // Saglabā jaunu darbinieku
    {
        $darbiniekis = new Darbinieki(); // Izveido jaunu darbinieka objektu

        $darbiniekis->Vards = $dati->input('Vards'); 
        // Saglabā vārdu no formas

        $darbiniekis->Uzvards = $dati->input('Uzvards'); 
        // Saglabā uzvārdu

        $darbiniekis->Parole = $dati->input('Parole'); 
        // Saglabā paroli

        $darbiniekis->Epasts = $dati->input('Epasts'); 
        // Saglabā e-pastu

        $darbiniekis->TelefonaNumurs = $dati->input('TelefonaNumurs'); 
        // Saglabā telefona numuru

        $darbiniekis->AmataID = $dati->input('AmataID'); 
        // Saglabā amata ID

        // $darbiniekis->Admin = $dati->input('Admin'); // Admin lauks (ja nepieciešams)

        $darbiniekis->save(); // Saglabā darbinieku datubāzē

        return redirect()->to('/Darbinieki')->with('success', 'Ieraksts tika pievienots');
        // Pāradresē uz darbinieku sarakstu
    }




public function edit($id) // Atver darbinieka rediģēšanas formu
{
    $darbiniekis = Darbinieki::find($id); // Atrod darbinieku pēc ID
    $amati = Amati::orderBy('AmataID','asc')->get(); // Iegūst visus amatus

    return view('DarbiniekiEdit', [
        'darbinieki' => $darbiniekis, // Nosūta darbinieka datus
        'amati' => $amati // Nosūta amatu sarakstu
    ]);
}




    public function editSubmit(Request $dati, $id) // Atjaunina darbinieka datus
    {

        DB::table('darbinieki')
            ->where('DarbiniekaID', $id) // Atrod darbinieku pēc ID
            ->update([
                'Vards' => $dati->input('Vards'), // Atjaunina vārdu
                'Uzvards' => $dati->input('Uzvards'), // Atjaunina uzvārdu
                'Parole' => $dati->input('Parole'), // Atjaunina paroli
                'Epasts' => $dati->input('Epasts'), // Atjaunina e-pastu
                'TelefonaNumurs' => $dati->input('TelefonaNumurs'), // Atjaunina telefona numuru
                'AmataID' => $dati->input('AmataID'), // Atjaunina amata ID
            ]);

         return redirect()->to('/Darbinieki')->with('success', 'Ieraksts tika atjaunināts');
         // Pāradresē uz darbinieku sarakstu
    }


    
}