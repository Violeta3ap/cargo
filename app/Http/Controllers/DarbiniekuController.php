<?php

namespace App\Http\Controllers;

use App\Models\Amati;
use App\Models\Darbinieki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pārvalda darbinieku ierakstu CRUD un piekļuves tiesības.
class DarbiniekuController extends Controller
{
    // Pārbauda vai parasts darbinieks drīkst mainīt darbinieku datus.
    private function darbinieksCannotModifyEmployees()
    {
        return auth()->check() && auth()->user()->isDarbinieks();
    }

    // Darbinieku saraksts.
    public function showAllDarbinieki()
    {
        $darbiniekis = new Darbinieki();
        return view('Darbinieki', ['darbiniekis' => $darbiniekis->orderBy('DarbiniekaID', 'asc')->get()]);
    }

    // Dzēš darbinieku.
    public function delete($id)
    {
        if ($this->darbinieksCannotModifyEmployees()) {
            return redirect('/Darbinieki')->with('error', 'Darbiniekam nav tiesību dzēst darbiniekus.');
        }

        DB::table('darbinieki')->where('DarbiniekaID', $id)->delete();
        return redirect('/Darbinieki')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu.
    public function create()
    {
        $amati = Amati::orderBy('AmataID', 'asc')->get();
        return view('DarbiniekuPiev', ['amati' => $amati]);
    }

    // Parāda darbinieka detaļas.
    public function details($id)
    {
        $darbiniekis = Darbinieki::find($id);
        return view('DarbiniekiApskate', ['darbinieki' => $darbiniekis]);
    }

    // Saglabā jaunu darbinieku.
    public function DarbiniekiSubmit(Request $dati)
    {
        // Izveido jaunu darbinieka ierakstu no formas datiem.
        $darbiniekis = new Darbinieki();
        $darbiniekis->Vards = $dati->input('Vards');
        $darbiniekis->Uzvards = $dati->input('Uzvards');
        $darbiniekis->Parole = $dati->input('Parole');
        $darbiniekis->Epasts = $dati->input('Epasts');
        $darbiniekis->TelefonaNumurs = $dati->input('TelefonaNumurs');
        $darbiniekis->AmataID = $dati->input('AmataID');
        $darbiniekis->save();

        return redirect()->to('/Darbinieki')->with('success', 'Ieraksts tika pievienots');
    }

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        if ($this->darbinieksCannotModifyEmployees()) {
            return redirect('/Darbinieki')->with('error', 'Darbiniekam nav tiesību rediģēt darbiniekus.');
        }

        $darbiniekis = Darbinieki::find($id);
        $amati = Amati::orderBy('AmataID', 'asc')->get();

        return view('DarbiniekiEdit', [
            'darbinieki' => $darbiniekis,
            'amati' => $amati,
        ]);
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        if ($this->darbinieksCannotModifyEmployees()) {
            return redirect('/Darbinieki')->with('error', 'Darbiniekam nav tiesību rediģēt darbiniekus.');
        }

        // Atjauno darbinieka laukus pēc ID.
        DB::table('darbinieki')
            ->where('DarbiniekaID', $id)
            ->update([
                'Vards' => $dati->input('Vards'),
                'Uzvards' => $dati->input('Uzvards'),
                'Parole' => $dati->input('Parole'),
                'Epasts' => $dati->input('Epasts'),
                'TelefonaNumurs' => $dati->input('TelefonaNumurs'),
                'AmataID' => $dati->input('AmataID'),
            ]);

        return redirect()->to('/Darbinieki')->with('success', 'Ieraksts tika atjaunināts');
    }
}