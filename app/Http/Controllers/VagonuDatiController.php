<?php

namespace App\Http\Controllers;

use App\Models\VagonuDati;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VagonuDatiController extends Controller
{
    // Vagonu datu saraksts.
    public function showAllVagonuDati()
    {
        $dati = new VagonuDati();
        return view('VagonuDati', ['dati' => $dati->orderBy('DatuID', 'asc')->get()]);
    }

    // Dzēš ierakstu.
    public function delete($id)
    {
        DB::table('vagonudati')->where('DatuID', $id)->delete();
        return redirect('/VagonuDati')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu.
    public function create()
    {
        return view('VagonuDatuPiev');
    }

    // Parāda ieraksta detaļas.
    public function details($id)
    {
        $datu = VagonuDati::find($id);
        return view('VagonuDatuApskate', ['vagonudati' => $datu]);
    }

    // Saglabā jaunu ierakstu.
    public function DatuSubmit(Request $dati)
    {
        $datu = new VagonuDati();
        $datu->NomasID = $dati->input('NomasID');
        $datu->VagonaID = $dati->input('VagonaID');
        $datu->save();

        return redirect()->to('/VagonuDati')->with('success', 'Ieraksts tika pievienots');
    }

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        $datu = VagonuDati::find($id);
        return view('VagonuDatuEdit', ['vagonudati' => $datu]);
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        DB::table('vagonudati')
            ->where('DatuID', $id)
            ->update([
                'NomasID' => $dati->input('NomasID'),
                'VagonaID' => $dati->input('VagonaID'),
            ]);

        return redirect()->to('/VagonuDati')->with('success', 'Ieraksts tika atjaunināts');
    }
}