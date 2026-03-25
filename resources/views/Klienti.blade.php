@extends('layout.app')

@section('content')

@php
    $vards = $vards ?? request('vards');
    $uzvards = $uzvards ?? request('uzvards');
    $uznemumanos = $uznemumanos ?? request('uznemumanos');
@endphp

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
    <h2>Klienti</h2>

    <!-- Lapas galvene -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Klienti/jauns">Izveidot jaunu klientu</a>
    </nav>
</div>

<!-- Meklēšanas logs -->
<form method="GET" action="/Klienti" class="klienti-filter-form" style="padding: 8px 10px;">
    <div class="filter-window">
        <h4>Meklēšana</h4>
        <div class="filter-row">
            <input type="text" name="vards" value="{{ $vards }}" placeholder="Vārds">
            <input type="text" name="uzvards" value="{{ $uzvards }}" placeholder="Uzvārds">
            <input type="text" name="uznemumanos" value="{{ $uznemumanos }}" placeholder="Uzņēmuma nosaukums">
            <button type="submit" class="filter-btn">Meklēt</button>
        </div>
    </div>
    <a href="/Klienti" class="filter-btn">Notīrīt</a>
</form>

<!-- Klientu saraksts -->
<table class="table table-striped" style="width:100%; border:1px solid #59c1cf; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
        <tr>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>E-pasts</th>
            <th>Telefona numurs</th>
            <th>Uzņēmuma nosaukums</th>
            <th>Juridiska adrese</th>
            <th>Registrācijas numurs</th>
            <th>Konta numurs</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($klientis as $item)
        <tr>
            <td>{{$item->Vards}}</td>
            <td>{{$item->Uzvards}}</td>
            <td>{{$item->Epasts}}</td>
            <td>{{$item->TelefonaNumurs}}</td>
            <td>{{$item->UznemumaNosaukums}}</td>
            <td>{{$item->JuridiskaAdrese}}</td>
            <td>{{$item->RegistracijasNumurs}}</td>
            <td>{{$item->KontaNumurs}}</td>
            <td>
                <div style="display: flex; justify-content: center; gap: 8px;">
                    <a href="/Klienti/{{ $item->KlientaID }}/edit"
                       style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf;"
                       class="btn btn-sm btn-warning">
                        Rediģēt
                    </a>
                    <a href="/Klienti/{{ $item->KlientaID }}/delete"
                       onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                       style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf;">
                        Dzēst
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($klientis->hasPages())
<div style="margin-top: 15px; display: flex; justify-content: center;">
    <nav class="klienti-pagination" aria-label="Klientu lapu navigācija">
        <a href="{{ $klientis->onFirstPage() ? '#' : $klientis->previousPageUrl() }}"
           class="page-btn {{ $klientis->onFirstPage() ? 'disabled' : '' }}"
           {{ $klientis->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
            &lsaquo; Iepriekšējā
        </a>

        @foreach ($klientis->getUrlRange(1, $klientis->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn number {{ $page == $klientis->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        <a href="{{ $klientis->hasMorePages() ? $klientis->nextPageUrl() : '#' }}"
           class="page-btn {{ $klientis->hasMorePages() ? '' : 'disabled' }}"
           {{ $klientis->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
            Nākamā &rsaquo;
        </a>
    </nav>
</div>
@endif

<!-- Klientu tabulas un paginācijas stili -->
<style>
    .klienti-filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
        align-items: flex-end;
    }

    .filter-window {
        border: 1px solid #59c1cf;
        border-radius: 10px;
        padding: 10px;
        background: #f8fdfe;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
        width: 100%;
    }

    .filter-window h4 {
        margin: 0 0 4px 0;
        font-size: 0.95rem;
    }

    .filter-row {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        align-items: center;
        overflow-x: auto;
    }

    .klienti-filter-form input {
        border: 1px solid #59c1cf;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 0.92rem;
        width: auto;
        box-sizing: border-box;
        flex: 0 0 220px;
    }

    .filter-btn {
        flex: 0 0 auto;
        padding: 4px 12px;
        font-size: 0.8rem;
        border-radius: 6px;
        border: 1px solid #59c1cf;
        background-color: #59c1cf;
        color: #000;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .filter-btn:hover {
        background-color: #a2e0ed;
    }

    .table {
        border-collapse: collapse;
    }

    .table thead {
        background-color: #59c1cf;
        color: white;
    }

    .table thead th {
        border: 1px solid #59c1cf;
        padding: 12px;
        font-weight: bold;
    }

    .table tbody tr:hover {
        background-color: #e8f5f7;
    }

    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    .klienti-pagination {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
    }

    .page-btn {
        border-radius: 8px;
        border: 1px solid #59c1cf;
        padding: 6px 12px;
        color: #000000;
        text-decoration: none;
        background: linear-gradient(to right, #59c1cf, #ffffff);
        white-space: nowrap;
        font-size: 0.92rem;
        line-height: 1;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .page-btn.number {
        min-width: 34px;
        text-align: center;
        padding: 6px 10px;
    }

    .page-btn:hover {
        background: #a2e0ed;
        color: #000;
        transform: translateY(-1px);
    }

    .page-btn.active {
        background: #59c1cf;
        color: #000;
        font-weight: 600;
    }

    .page-btn.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>

@endsection

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif
