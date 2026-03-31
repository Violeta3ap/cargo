@extends('layout.app')

@section('content')
<div class="page-noslogojums">

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <div style="display: flex; align-items: center; gap: 18px;">
        <a href="/Noslogojums" style="font-size: 1.25rem; font-weight: 600; color: #2c7d89; text-decoration: none;">Dienas noslogojums</a>
        <a href="/Noslogojums/periods" style="font-size: 1.5rem; font-weight: 700; color: #000; text-decoration: none;">Perioda noslogojums</a>
    </div>

    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noma">Atpakaļ</a>
    </nav>
</div>

@if(session('success'))
    <div class="nos-alert nos-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="nos-alert nos-alert-danger">{{ session('error') }}</div>
@endif

<form method="GET" action="/Noslogojums/periods" style="margin-bottom: 20px;" id="period-load-form">
    <div style="border: 1px solid #59c1cf; border-radius: 10px; padding: 12px 16px; background: #f8fdfe; display: inline-flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <label for="perioda_sakums" style="font-weight: 500; white-space: nowrap;">No:</label>
        <input type="text" id="perioda_sakums" name="perioda_sakums" value="{{ $periodaSakums }}" autocomplete="off"
               style="border: 1px solid #59c1cf; border-radius: 6px; padding: 6px 10px; font-size: 14px; min-width: 120px;">

        <label for="perioda_beigas" style="font-weight: 500; white-space: nowrap;">Līdz:</label>
        <input type="text" id="perioda_beigas" name="perioda_beigas" value="{{ $periodaBeigas }}" autocomplete="off"
               style="border: 1px solid #59c1cf; border-radius: 6px; padding: 6px 10px; font-size: 14px; min-width: 120px;">

        <button type="submit"
                style="border-radius: 8px; border: 1px solid #59c1cf; padding: 6px 14px; background: #59c1cf; color: #000; cursor: pointer; font-size: 14px;">
            Skatīt periodu
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

    flatpickr('#perioda_sakums', {
        locale: 'lv',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: true
    });

    flatpickr('#perioda_beigas', {
        locale: 'lv',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: true
    });
});
</script>

<p style="color: #555; font-size: 14px; margin-bottom: 12px;">
    Izvēlētais periods: <strong>{{ \Carbon\Carbon::parse($periodaSakums)->format('d.m.Y') }}</strong> - <strong>{{ \Carbon\Carbon::parse($periodaBeigas)->format('d.m.Y') }}</strong>
</p>

@if($periodaKopsavilkums->isEmpty())
    <div style="border: 1px solid #59c1cf; border-radius: 10px; padding: 20px; background: #f8fdfe; text-align: center; color: #555;">
        Izvēlētajā periodā nav aktīvu nomu.
    </div>
@else
    @if(Auth::check() && Auth::user()->isKlients())
    <!-- Detalizētais saraksts tikai klientam -->
    <h3 style="margin-bottom: 10px;">Perioda nomas</h3>
    <table class="nos-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Vagona veids</th>
                <th>Maksimāli nomātie vagoni periodā</th>
                <th>Kopā pieejami</th>
                <th>Brīvie vagoni (pēc maksimuma)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($periodaKopsavilkums as $row)
                @php
                    $maksNomati = (int) $row->MaksimaliNomatiVagoni;
                    $kopejais = (int) $row->KopejaisVagonuSkaits;
                    $brivi = max(0, $kopejais - $maksNomati);
                @endphp
                <tr>
                    <td><strong>{{ $row->VeidaNosaukums }}</strong></td>
                    <td style="text-align: center;">{{ $maksNomati }}</td>
                    <td style="text-align: center;">{{ $kopejais }}</td>
                    <td style="text-align: center;">{{ $brivi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(Auth::check() && Auth::user()->isAdmin())
    <!-- Detalizētais saraksts tikai administratoram -->
    <h3 style="margin-bottom: 10px;">Perioda nomas</h3>
    <table class="nos-table" style="width: 100%; margin-bottom: 10px;">
        <thead>
            <tr>
                <th>Vagona veids</th>
                <th>Maksimāli nomātie vagoni periodā</th>
                <th>Kopā pieejami</th>
                <th>Brīvie vagoni (pēc maksimuma)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($periodaKopsavilkums as $row)
                @php
                    $maksNomati = (int) $row->MaksimaliNomatiVagoni;
                    $kopejais = (int) $row->KopejaisVagonuSkaits;
                    $brivi = max(0, $kopejais - $maksNomati);
                @endphp
                <tr>
                    <td><strong>{{ $row->VeidaNosaukums }}</strong></td>
                    <td style="text-align: center;">{{ $maksNomati }}</td>
                    <td style="text-align: center;">{{ $kopejais }}</td>
                    <td style="text-align: center;">{{ $brivi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
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
