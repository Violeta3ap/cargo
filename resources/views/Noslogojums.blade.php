@extends('layout.app')

@section('content')
<div class="page-noslogojums">


<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Vagonu noslogojums</h2>


        <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noma">Atpakaļ</a>

    @if(Auth::check() && Auth::user()->isAdmin())
            <a href="/Noslogojums/sync"
               onclick="return confirm('Sinhronizēt visus noslogojuma datus no nomas tabulas?');"
               style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff);">
                Sinhronizēt datus
            </a>
        </nav>
    @endif
</div>

@if(session('success'))
    <div class="nos-alert nos-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="nos-alert nos-alert-danger">{{ session('error') }}</div>
@endif

<!-- Datuma izvēles forma -->
<form method="GET" action="/Noslogojums" style="margin-bottom: 20px;">
    <div style="border: 1px solid #59c1cf; border-radius: 10px; padding: 12px 16px; background: #f8fdfe; display: inline-flex; gap: 12px; align-items: center;">
        <label for="datums" style="font-weight: 500; white-space: nowrap;">Izvēlieties datumu:</label>
        <input type="text" id="datums" name="datums" value="{{ $datums }}" autocomplete="off"
               style="border: 1px solid #59c1cf; border-radius: 6px; padding: 6px 10px; font-size: 14px;">
        <button type="submit"
                style="border-radius: 8px; border: 1px solid #59c1cf; padding: 6px 14px; background: #59c1cf; color: #000; cursor: pointer; font-size: 14px;">
            Skatīt
        </button>
    </div>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === 'undefined') {
        return;
    }

    flatpickr('#datums', {
        locale: 'lv',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: true
    });
});
</script>

<p style="color: #555; font-size: 14px; margin-bottom: 20px;">
    Rādīts datums: <strong>{{ \Carbon\Carbon::parse($datums)->format('d.m.Y') }}</strong>
</p>

@if($kopsavilkums->isEmpty())
    <div style="border: 1px solid #59c1cf; border-radius: 10px; padding: 20px; background: #f8fdfe; text-align: center; color: #555;">
        Izvēlētajā datumā nav aktīvu nomu.
    </div>
@else

    <!-- Kopsavilkums pa vagonu veidiem -->
    <h3 style="margin-bottom: 10px;">Kopsavilkums pa vagonu veidiem</h3>
    <table class="nos-table" style="width: 100%; margin-bottom: 30px;">
        <thead>
            <tr>
                <th>Vagona veids</th>
                <th>Nomātie vagoni</th>
                <th>Kopā pieejami</th>
                <th>Brīvie vagoni</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kopsavilkums as $row)
                @php
                    $nomati   = (int) $row->NomatiVagoni;
                    $kopejais = (int) $row->KopejaisVagonuSkaits;
                    $brivi    = max(0, $kopejais - $nomati);
                @endphp
                <tr>
                    <td><strong>{{ $row->VeidaNosaukums }}</strong></td>
                    <td style="text-align: center;">{{ $nomati }}</td>
                    <td style="text-align: center;">{{ $kopejais }}</td>
                    <td style="text-align: center;">{{ $brivi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detalizētais saraksts -->
    <h3 style="margin-bottom: 10px;">Aktīvās nomas</h3>
    <table class="nos-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Nomas Nr.</th>
                <th>Vagona veids</th>
                <th>Iznomāto vagonu skaits</th>
                <th>Nomas sākuma periods</th>
                <th>Nomas beigu periods</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detali as $row)
                <tr>
                    <td>{{ $row->NomasID }}</td>
                    <td>{{ $row->VeidaNosaukums }}</td>
                    <td style="text-align: center;">{{ $row->VagonuSkaits }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($row->NomasSakumaPeriods)->format('d.m.Y') }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($row->NomasBeiguPeriods)->format('d.m.Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif

</div>

<style>
    .page-noslogojums .nos-alert {
        padding: 12px 16px;
        margin-bottom: 15px;
        border-radius: 8px;
        font-size: 14px;
    }
    .page-noslogojums .nos-alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    .page-noslogojums .nos-alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    .page-noslogojums .nos-table {
        border-collapse: collapse;
        border: 1px solid #59c1cf;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
    }
    .page-noslogojums .nos-table thead {
        background-color: #59c1cf;
        color: #fff;
    }
    .page-noslogojums .nos-table thead th {
        padding: 12px 14px;
        font-weight: bold;
        border: 1px solid #59c1cf;
        text-align: center;
    }
    .page-noslogojums .nos-table tbody td {
        padding: 10px 14px;
        border: 1px solid #ddd;
        text-align: center;
    }
    .page-noslogojums .nos-table tbody tr:hover {
        background-color: #e8f5f7;
    }
</style>

@endsection
