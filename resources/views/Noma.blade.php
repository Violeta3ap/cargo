@extends('layout.app')

@section('content')

@php
    $klientaVards = $klientaVards ?? request('klienta_vards');
    $klientaUzvards = $klientaUzvards ?? request('klienta_uzvards');
    $klientaUznemums = $klientaUznemums ?? request('klienta_uznemums');
    $krava = $krava ?? request('krava');
    $veids = $veids ?? request('veids');
    $periodsNo = $periodsNo ?? request('periods_no');
    $periodsLidz = $periodsLidz ?? request('periods_lidz');
@endphp

<!-- Lapas galvene -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Noma</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noma/jauns">Jauns ieraksts</a>

          <a type="button" onclick="window.print()" title="Printēt dokumentu" class="print-btn"><i class="fas fa-print"></i> Drukāt</a>
         <!-- Papildsaite rezervē -->
    </nav>
</div>

<!-- Meklēšanas un filtrēšanas logi -->
<form method="GET" action="/Noma" class="noma-filter-form" style="padding: 8px 10px;">
    <div class="filter-window">
        <h4>Meklēšana (klients)</h4>
        <input type="text" name="klienta_vards" value="{{ $klientaVards }}" placeholder="Klienta vārds">
        <input type="text" name="klienta_uzvards" value="{{ $klientaUzvards }}" placeholder="Klienta uzvārds">
        <input type="text" name="klienta_uznemums" value="{{ $klientaUznemums }}" placeholder="Klienta uzņēmums">
        <button type="submit" class="filter-btn">Meklēt</button>
    </div>

    <div class="filter-window">
        <h4>Filtrēšana (noma)</h4>
        <input type="text" name="krava" value="{{ $krava }}" placeholder="Kravas nosaukums">
        <input type="text" name="veids" value="{{ $veids }}" placeholder="Vagona tips">
        <input type="date" name="periods_no" value="{{ $periodsNo }}" title="Periods no">
        <input type="date" name="periods_lidz" value="{{ $periodsLidz }}" title="Periods līdz">
        <button type="submit" class="filter-btn">Filtrēt</button>
    </div>
    <a href="/Noma" class="filter-btn">Notīrīt</a>
</form>

<!-- Nomas saraksts -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Klients</th>
            <th>Klienta uzņēmums</th>
            <th>Kravas veids</th>
            <th>Vagona nosaukums</th>
            <th>Vagonu skaits</th>
            <th>Nomas sākuma periods</th>
            <th>Nomas beigu periods</th>
            <th>Kopējā maksa</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($noma as $item)
        <tr>
            <td>{{$item->klienti->Vards ?? ('ID: '.$item->KlientaID)}} {{$item->klienti->Uzvards ?? ''}}</td>
            <td>{{$item->klienti->UznemumaNosaukums ?? ('ID: '.$item->KlientaID)}}</td>
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID)}}</td>
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID)}}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->NomasSakumaPeriods}}</td>
            <td>{{$item->NomasBeiguPeriods}}</td>
            <td>{{$item->KopejaMaksa}}</td>
            <td>
                <div style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                    <a href="/Noma/{{ $item->NomasID }}/edit" class="btn-action">Rediģēt</a>
                    <a href="/Noma/{{ $item->NomasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($noma->hasPages())
<div style="margin-top: 15px; display: flex; justify-content: center;">
    <nav class="noma-pagination" aria-label="Nomas lapu navigācija">
        <a href="{{ $noma->onFirstPage() ? '#' : $noma->previousPageUrl() }}"
           class="page-btn {{ $noma->onFirstPage() ? 'disabled' : '' }}"
           {{ $noma->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
            &lsaquo; Iepriekšējā
        </a>

        @foreach ($noma->getUrlRange(1, $noma->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn number {{ $page == $noma->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        <a href="{{ $noma->hasMorePages() ? $noma->nextPageUrl() : '#' }}"
           class="page-btn {{ $noma->hasMorePages() ? '' : 'disabled' }}"
           {{ $noma->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
            Nākamā &rsaquo;
        </a>
    </nav>
</div>
@endif

<!-- Noma tabulas un paginācijas stili -->
<style>
    .print-btn {
        border-radius: 8px;
        border: 1px solid #59c1cf;
        padding: 5px 10px;
        color: #000;
        text-decoration: none;
        background-color: #59c1cf;
        white-space: nowrap;
        margin-left: 8px;
    }

    .print-btn:hover {
        background-color: #a2e0ed;
    }

    .table {
        border-collapse: collapse;
    }

    .noma-filter-form {
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
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        flex: 1 1 360px;
    }

    .filter-window h4 {
        width: 100%;
        margin: 0 0 4px 0;
        font-size: 0.95rem;
    }

    .noma-filter-form input {
        border: 1px solid #59c1cf;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 0.92rem;
        width: auto;
        box-sizing: border-box;
        flex: 1 1 180px;
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

    /* Darbību pogas */
    .btn-action {
        border-radius: 8px;
        border: 1px solid #59c1cf;
        padding: 5px 10px;
        color: #000000;
        text-decoration: none;
        background-color: #59c1cf;
        white-space: nowrap;
        font-size: 0.9rem;
        width: 100%;
        text-align: center;
    }

    .btn-action:hover {
        background-color: #a2e0ed;
        color: #000;
    }

    .noma-pagination {
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

    @media print {
        .page-wrapper > div:first-child,
        .navigacija,
        .noma-filter-form,
        .noma-pagination,
        footer,
        .alert,
        .btn-action,
        th:last-child,
        td:last-child {
            display: none !important;
        }

        h2 {
            margin: 0 0 12px 0;
            font-size: 20px;
        }

        .table {
            width: 100% !important;
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table thead {
            background: #fff !important;
            color: #000 !important;
        }

        .table thead th,
        .table tbody td {
            border: 1px solid #000 !important;
            padding: 6px !important;
        }

        .table tbody tr:hover {
            background: transparent !important;
        }
    }
</style>

@endsection

@if(session('success'))
    <div class="alert alert-success" style="margin-top: 10px;">
        {{ session('success') }}
    </div>  
@endif
