<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noma;
use Illuminate\Support\Facades\DB;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;

class NomaController extends Controller
{
    private function validateVagonuSkaitsLimit(Request $dati): ?string
    {
        $veidaId = (int) $dati->input('VeidaID');
        $pieprasitsSkaits = (int) $dati->input('VagonuSkaits');

        $veids = Veidi::find($veidaId);

        if (!$veids) {
            return 'Izvēlētais vagona veids nav atrasts.';
        }

        if ($pieprasitsSkaits > (int) $veids->VagonuSkaits) {
            return 'Vagonu skaits nevar būt lielāks par izvēlētā veida pieejamo skaitu (' . $veids->VagonuSkaits . ').';
        }

        return null;
    }

    // Nomas saraksts ar pagināciju un meklēšanu.
    public function showAllNoma(Request $request)
    {
        $klients = trim((string) $request->query('klients', ''));
        $krava = trim((string) $request->query('krava', ''));
        $veidaid = trim((string) $request->query('veidaid', ''));
        $periods = trim((string) $request->query('periods', ''));

        $query = Noma::query()
            ->with(['klienti', 'kravas', 'veidi'])
            ->leftJoin('klienti', 'vagonunoma.KlientaID', '=', 'klienti.KlientaID')
            ->leftJoin('krava', 'vagonunoma.KravasID', '=', 'krava.KravasID')
            ->select('vagonunoma.*');

        if ($klients !== '') {
            $query->where(function ($q) use ($klients) {
                $q->where('klienti.Vards', 'like', '%' . $klients . '%')
                    ->orWhere('klienti.Uzvards', 'like', '%' . $klients . '%')
                    ->orWhere('klienti.UznemumaNosaukums', 'like', '%' . $klients . '%');
            });
        }

        if ($krava !== '') {
            $query->where('krava.Nosaukums', 'like', '%' . $krava . '%');
        }

        if ($veidaid !== '' && ctype_digit($veidaid)) {
            $query->where('vagonunoma.VeidaID', (int) $veidaid);
        }

        if ($periods !== '') {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $periods)) {
                $query->whereDate('vagonunoma.NomasSakumaPeriods', '<=', $periods)
                    ->whereDate('vagonunoma.NomasBeiguPeriods', '>=', $periods);
            } else {
                $query->where(function ($q) use ($periods) {
                    $q->where('vagonunoma.NomasSakumaPeriods', 'like', '%' . $periods . '%')
                        ->orWhere('vagonunoma.NomasBeiguPeriods', 'like', '%' . $periods . '%');
                });
            }
        }

        $noma = $query
            ->orderBy('vagonunoma.NomasID', 'asc')
            ->paginate(5)
            ->appends($request->query());

        return view('Noma', compact('noma', 'klients', 'krava', 'veidaid', 'periods'));
    }

    // Dzēš nomas ierakstu.
    public function delete($id)
    {
        DB::table('vagonunoma')->where('NomasID', $id)->delete();
        return redirect('/Noma')->with('success', 'Ieraksts tika dzēsts');
    }

    // Atver pievienošanas formu ar saistītajiem sarakstiem.
    public function create()
    {
        $klienti = Klienti::all();
        $kravas = Kravas::all();
        $veidi = Veidi::all();

        return view('NomaPiev', compact('klienti','kravas','veidi'));
    }

    // Parāda viena ieraksta detaļas.
    public function details($id)
    {
        $noma = Noma::find($id);
        return view('NomaApskate', ['noma' => $noma]);
    }

    // Saglabā jaunu nomas ierakstu.
    public function NomaSubmit(Request $dati)
    {
        $dati->validate([
            'KlientaID' => ['required', 'integer'],
            'KravasID' => ['required', 'integer'],
            'Svars' => ['required', 'numeric', 'min:1'],
            'VeidaID' => ['required', 'integer'],
            'VagonuSkaits' => ['required', 'integer', 'min:1'],
            'NomasSakumaPeriods' => ['required', 'date'],
            'NomasBeiguPeriods' => ['required', 'date'],
            'KopejaMaksa' => ['required', 'numeric', 'min:1'],
        ]);

        $vagonuSkaitsError = $this->validateVagonuSkaitsLimit($dati);
        if ($vagonuSkaitsError) {
            return back()->withInput()->withErrors(['VagonuSkaits' => $vagonuSkaitsError]);
        }

        $noma = new Noma();
        $noma->KlientaID = $dati->input('KlientaID');
        $noma->KravasID = $dati->input('KravasID');
        $noma->Svars = $dati->input('Svars');
        $noma->VeidaID = $dati->input('VeidaID');
        $noma->VagonuSkaits = $dati->input('VagonuSkaits');
        $noma->NomasSakumaPeriods = $dati->input('NomasSakumaPeriods');
        $noma->NomasBeiguPeriods = $dati->input('NomasBeiguPeriods');
        $noma->KopejaMaksa = $dati->input('KopejaMaksa');
        $noma->save();

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika pievienots');
    }

    // Atver rediģēšanas formu.
    public function edit($id)
    {
        $noma = Noma::find($id);
        $klienti = Klienti::all();
        $kravas = Kravas::all();
        $veidi = Veidi::all();

        return view('NomaEdit', compact('noma','klienti','kravas','veidi'));
    }

    // Saglabā rediģētas vērtības.
    public function editSubmit(Request $dati, $id)
    {
        $dati->validate([
            'KlientaID' => ['required', 'integer'],
            'KravasID' => ['required', 'integer'],
            'Svars' => ['required', 'numeric', 'min:1'],
            'VeidaID' => ['required', 'integer'],
            'VagonuSkaits' => ['required', 'integer', 'min:1'],
            'NomasSakumaPeriods' => ['required', 'date'],
            'NomasBeiguPeriods' => ['required', 'date'],
            'KopejaMaksa' => ['required', 'numeric', 'min:1'],
        ]);

        $vagonuSkaitsError = $this->validateVagonuSkaitsLimit($dati);
        if ($vagonuSkaitsError) {
            return back()->withInput()->withErrors(['VagonuSkaits' => $vagonuSkaitsError]);
        }

        DB::table('vagonunoma')
            ->where('NomasID', $id)
            ->update([
                'KlientaID' => $dati->input('KlientaID'),
                'KravasID' => $dati->input('KravasID'),
                'Svars' => $dati->input('Svars'),
                'VeidaID' => $dati->input('VeidaID'),
                'VagonuSkaits' => $dati->input('VagonuSkaits'),
                'NomasSakumaPeriods' => $dati->input('NomasSakumaPeriods'),
                'NomasBeiguPeriods' => $dati->input('NomasBeiguPeriods'),
                'KopejaMaksa' => $dati->input('KopejaMaksa'),
            ]);

        return redirect()->to('/Noma')->with('success', 'Ieraksts tika atjaunināts');
    }
}
