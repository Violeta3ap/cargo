<?php

namespace App\Http\Controllers;

use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeidiController extends Controller
{
    private function klientsCannotModify()
    {
        return auth()->check() && auth()->user()->isKlients();
    }

    // Veidu saraksts.
    public function showAllVeidi()
    {
        $veidi = new Veidi();
        return view('Veidi', ['dati' => $veidi->orderBy('VeidaID', 'asc')->get()]);
    }

    // Dzēš veidu.
    public function delete($id)
    {
        if ($this->klientsCannotModify()) {
            return redirect('/Veidi')->with('error', 'Klientam nav tiesību dzēst ierakstus.');
        }

        DB::table('veidi')->where('VeidaID', $id)->delete();
        return redirect('/Veidi')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu.
    public function create()
    {
        if ($this->klientsCannotModify()) {
            return redirect('/Veidi')->with('error', 'Klientam nav tiesību pievienot ierakstus.');
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
        if ($this->klientsCannotModify()) {
            return redirect('/Veidi')->with('error', 'Klientam nav tiesību pievienot ierakstus.');
        }

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
        if ($this->klientsCannotModify()) {
            return redirect('/Veidi')->with('error', 'Klientam nav tiesību rediģēt ierakstus.');
        }

        $veidi = Veidi::find($id);
        return view('VeidiEdit', ['veidi' => $veidi]);
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        if ($this->klientsCannotModify()) {
            return redirect('/Veidi')->with('error', 'Klientam nav tiesību rediģēt ierakstus.');
        }

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