<?php

namespace App\Http\Controllers; // Norāda controller mapes vietu Laravel projektā

use Illuminate\Http\Request; // Klase formu datu saņemšanai
use App\Models\Klienti; // Modelis darbam ar klientu tabulu
use Illuminate\Support\Facades\DB; // Ļauj izmantot DB komandas

class KlientiController extends Controller // Izveido controller klasi
{



    public function showAllKlienti() // Parāda visus klientus
    {
     $klientis= new Klienti(); // Izveido Klienti objekta instanci
     //dd($data->all()); // Debug funkcija datu pārbaudei
       return view('Klienti', ['klientis' => $klientis->orderBy('KlientaID', 'asc')->paginate(5)]);
       // Atver Klienti lapu un nosūta klientu sarakstu sakārtotu pēc ID
    }



    public function delete($id) // Funkcija klienta dzēšanai
    {
      DB::table('klienti')->where('KlientaID', $id)->delete(); 
      // Dzēš klientu no datubāzes pēc ID

      return redirect('/Klienti')->with('success', 'Ieraksts tika dzēsts');
      // Pāradresē uz klientu sarakstu ar paziņojumu
    }



    public function create() // Atver klienta pievienošanas formu
    {
      return view('KlientuPiev'); 
      // Atver lapu jauna klienta pievienošanai
    }



    public function details($id) // Parāda viena klienta informāciju
    {
      $klientis = Klienti::find($id); // Atrod klientu pēc ID
      return view('KlientiApskate', ['klientis' => $klientis]);
      // Atver apskates lapu un nosūta klienta datus
    }



    public function KlientiSubmit(Request $dati) // Saglabā jaunu klientu
    {
        $klientis = new Klienti(); // Izveido jaunu klienta objektu

        $klientis->Vards = $dati->input('Vards'); 
        // Saglabā klienta vārdu

        $klientis->Uzvards = $dati->input('Uzvards'); 
        // Saglabā klienta uzvārdu

        $klientis->Parole = $dati->input('Parole'); 
        // Saglabā paroli

        $klientis->Epasts = $dati->input('Epasts'); 
        // Saglabā e-pastu

        $klientis->TelefonaNumurs = $dati->input('TelefonaNumurs'); 
        // Saglabā telefona numuru

        $klientis->UznemumaNosaukums = $dati->input('UznemumaNosaukums'); 
        // Saglabā uzņēmuma nosaukumu

        $klientis->JuridiskaAdrese = $dati->input('JuridiskaAdrese'); 
        // Saglabā juridisko adresi

        $klientis->RegistracijasNumurs = $dati->input('RegistracijasNumurs'); 
        // Saglabā reģistrācijas numuru

        $klientis->KontaNumurs = $dati->input('KontaNumurs'); 
        // Saglabā bankas konta numuru

        // $klientis->Admin = $dati->input('Admin'); // Admin lauks (ja nepieciešams)

        $klientis->save(); // Saglabā klientu datubāzē

        return redirect()->to('/Klienti')->with('success', 'Ieraksts tika pievienots');
        // Pāradresē uz klientu sarakstu
    }



    public function edit($id) // Atver klienta rediģēšanas formu
    {
     $klientis = Klienti::find($id); // Atrod klientu pēc ID
       return view('KlientiEdit', ['klientis' => $klientis]);
       // Atver rediģēšanas lapu un nosūta klienta datus
    }




    public function editSubmit(Request $dati, $id) // Atjaunina klienta datus
    {

        DB::table('klienti')
            ->where('KlientaID', $id) // Atrod klientu pēc ID
            ->update([
                'Vards' => $dati->input('Vards'), // Atjaunina vārdu
                'Uzvards' => $dati->input('Uzvards'), // Atjaunina uzvārdu
                'Parole' => $dati->input('Parole'), // Atjaunina paroli
                'Epasts' => $dati->input('Epasts'), // Atjaunina e-pastu
                'TelefonaNumurs' => $dati->input('TelefonaNumurs'), // Atjaunina telefona numuru
                'UznemumaNosaukums' => $dati->input('UznemumaNosaukums'), // Atjaunina uzņēmuma nosaukumu
                'JuridiskaAdrese' => $dati->input('JuridiskaAdrese'), // Atjaunina juridisko adresi
                'RegistracijasNumurs' => $dati->input('RegistracijasNumurs'), // Atjaunina reģistrācijas numuru
                'KontaNumurs' => $dati->input('KontaNumurs'), // Atjaunina bankas konta numuru
            ]);

         return redirect()->to('/Klienti')->with('success', 'Ieraksts tika atjaunināts');
         // Pāradresē uz klientu sarakstu
    }


    

}