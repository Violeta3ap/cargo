<?php

namespace App\Http\Controllers; // Norāda controller mapes vietu Laravel projektā

use Illuminate\Http\Request; // Klase formu datu saņemšanai
use App\Models\Kravas; // Modelis darbam ar kravas tabulu
use Illuminate\Support\Facades\DB; // Ļauj izmantot DB komandas
use App\Models\Veidi; // Veidu modelis
class KravasController extends Controller // Izveido controller klasi
{



    public function showAllKrava() // Parāda visas kravas
    {
     $kravas= new Kravas(); // Izveido Kravas objekta instanci
     //dd($data->all()); // Debug funkcija datu pārbaudei
       return view('Kravas', ['dati' => $kravas->orderBy('KravasID', 'asc')->get()]);
       // Atver Kravas lapu un nosūta kravu sarakstu sakārtotu pēc ID
    }




    public function delete($id) // Funkcija kravas dzēšanai
    {
      DB::table('krava')->where('KravasID', $id)->delete(); 
      // Dzēš kravu no datubāzes pēc ID

      return redirect('/Kravas')->with('success', 'Ieraksts tika dzēsts');
      // Pāradresē uz kravu sarakstu ar paziņojumu
    }



    public function create() // Atver kravas pievienošanas formu
    

        $veidi = Veidi::all();     // paņem visus veidus 

    return view('KravasPiev', compact('veidi'));
      // Atver lapu jaunas kravas pievienošanai
    }




    public function details($id) // Parāda vienas kravas informāciju
    {
      $kravas = Kravas::find($id); // Atrod kravu pēc ID
      return view('KravasApskate', ['kravas' => $kravas]);
      // Atver apskates lapu un nosūta kravas datus
    }



    public function DatuSubmit(Request $dati) // Saglabā jaunu kravu
    {
        $kravas = new Kravas(); // Izveido jaunu kravas objektu

        $kravas->Nosaukums = $dati->input('Nosaukums'); 
         $raksturojums->VeidaID = $dati->input('VeidaID'); // Iestata veida ID

        // Saglabā kravas nosaukumu no formas

        $kravas->save(); // Saglabā kravu datubāzē

        return redirect()->to('/Kravas')->with('success', 'Ieraksts tika pievienots');
        // Pāradresē uz kravu sarakstu
    }




    public function edit($id) // Atver kravas rediģēšanas formu
    {
     $kravas = Kravas::find($id); // Atrod kravu pēc ID
    $veidi = Veidi::all();  

    

         return view('KravasEdit',  compact('kravas','veidi')

       // Atver rediģēšanas lapu un nosūta kravas datus
    }




    public function editSubmit(Request $dati, $id) // Atjaunina kravas datus
    {
        DB::table('krava')
            ->where('KravasID', $id) // Atrod kravu pēc ID
            ->update([
                'Nosaukums' => $dati->input('Nosaukums'), 
                              'VeidaID' => $dati->input('VeidaID'), // Atjaunina veida ID

                // Atjaunina kravas nosaukumu
            ]);

         return redirect()->to('/Kravas')->with('success', 'Ieraksts tika atjaunināts');
         // Pāradresē uz kravu sarakstu
    }


    
}
