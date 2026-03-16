<?php

namespace App\Http\Controllers; // Norāda, ka šis fails ir Laravel controller mapē

use Illuminate\Http\Request; // Ielādē klasi, kas apstrādā formu datus
use App\Models\Amati; // Ielādē Amati modeli darbam ar datubāzi
use Illuminate\Support\Facades\DB; // Ļauj izmantot DB komandas

class AmataController extends Controller // Izveido controller klasi
{
    public function showAllAmati() // Funkcija, kas parāda visus amatus
    {
     $amats= new Amati(); // Izveido Amati objekta instanci
     //dd($data->all()); // Debug funkcija datu pārbaudei (šobrīd izslēgta)
       return view('Amati', ['dati' => $amats->orderBy('AmataID', 'asc')->get()]);
       // Atver Amati skata failu un nosūta visus amatus sakārtotus pēc ID
    }

    public function delete($id) // Funkcija ieraksta dzēšanai
    {
      DB::table('amats')->where('AmataID', $id)->delete(); 
      // Dzēš ierakstu no tabulas pēc AmataID

      return redirect('/Amati')->with('success', 'Ieraksts tika dzēsts');
      // Pāradresē uz Amati lapu ar ziņojumu
    }

    public function create() // Funkcija pievienošanas formas atvēršanai
    {
      return view('AmataPiev'); 
      // Atver lapu, kur var pievienot jaunu amatu
    }

    public function details($id) // Funkcija viena amata apskatei
    {
      $amats = Amati::find($id); // Atrod ierakstu pēc ID
      return view('AmataApskate', ['amati' => $amats]);
      // Atver apskates lapu un nosūta amata datus
    }

    public function DatuSubmit(Request $dati) // Funkcija datu saglabāšanai no formas
    {
        $amats = new Amati(); // Izveido jaunu Amati objektu
        $amats->Nosaukums = $dati->input('Nosaukums'); 
        // Paņem ievadīto nosaukumu no formas

        $amats->save(); // Saglabā ierakstu datubāzē

        return redirect()->to('/Amati')->with('success', 'Ieraksts tika pievienots');
        // Pāradresē uz Amati sarakstu ar paziņojumu
    }

    public function edit($id) // Funkcija rediģēšanas formas atvēršanai
    {
     $amats = Amati::find($id); // Atrod amatu pēc ID
       return view('AmataEdit', ['amati' => $amats]);
       // Atver rediģēšanas lapu un nosūta amata datus
    }

    public function editSubmit(Request $dati, $id) // Funkcija datu atjaunināšanai
    {
        DB::table('amats')
            ->where('AmataID', $id) // Atrod ierakstu pēc ID
            ->update([
                'Nosaukums' => $dati->input('Nosaukums'), 
                // Atjaunina amata nosaukumu
            ]);

         return redirect()->to('/Amati')->with('success', 'Ieraksts tika atjaunināts');
         // Pāradresē uz Amati sarakstu ar paziņojumu
    }
}