<?php

namespace App\Http\Controllers;

use App\Models\Kravas;
use App\Models\VagonaRaksturojums;
use App\Models\Veidi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VagonaRaksturojumsController extends Controller
{
    // Vagonu raksturojumu saraksts.
    public function showAllVagonaRaksturojums()
    {
        $dati = new VagonaRaksturojums();
        return view('VagonaRaksturojums', ['dati' => $dati->orderBy('VagonaID', 'asc')->get()]);
    }

    // Dzēš ierakstu.
    public function delete($id)
    {
        DB::table('vagonaraksturojums')->where('VagonaID', $id)->delete();
        return redirect('/VagonaRaksturojums')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu.
    public function create()
    {
        $veidi = Veidi::all();
        $kravas = Kravas::all();

        return view('VagonaRaksturojumaPiev', compact('veidi', 'kravas'));
    }

    // Parāda ieraksta detaļas.
    public function details($id)
    {
        $raksturojums = VagonaRaksturojums::find($id);
        return view('VagonaRaksturojumaApskate', ['vagonaraksturojums' => $raksturojums]);
    }

    // Saglabā jaunu ierakstu.
    public function DatuSubmit(Request $dati)
    {
        $raksturojums = new VagonaRaksturojums();
        $raksturojums->VeidaID = $dati->input('VeidaID');
        $raksturojums->KravasID = $dati->input('KravasID');
        $raksturojums->Celtspeja = $dati->input('Celtspeja');
        $raksturojums->VagonaNumurs = $dati->input('VagonaNumurs');
        $raksturojums->save();

        return redirect()->to('/VagonaRaksturojums')->with('success', 'Ieraksts tika pievienots');
    }

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        $vagonaraksturojums = VagonaRaksturojums::find($id);
        $veidi = Veidi::all();
        $kravas = Kravas::all();

        return view('VagonaRaksturojumaEdit', compact('vagonaraksturojums', 'veidi', 'kravas'));
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        DB::table('vagonaraksturojums')
            ->where('VagonaID', $id)
            ->update([
                'VeidaID' => $dati->input('VeidaID'),
                'KravasID' => $dati->input('KravasID'),
                'Celtspeja' => $dati->input('Celtspeja'),
                'VagonaNumurs' => $dati->input('VagonaNumurs'),
            ]);

        return redirect()->to('/VagonaRaksturojums')->with('success', 'Ieraksts tika atjaunināts');
    }
}