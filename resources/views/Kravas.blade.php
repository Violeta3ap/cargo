@extends('layout.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Krāvu veidi</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">

             @if(Auth::check()) <!-- ja lietotājs ir pieteicies -->
            @if(Auth::user()->isAdmin())
                {{-- Administrators redz visas saites --}}
                <a href="/Kravas/jauns">Jauns ieraksts</a>
            @elseif(Auth::user()->isDarbinieks())
                {{-- Darbinieks redz tikai pieļaujamās saites --}}
                <a href="/Kravas/jauns">Jauns ieraksts</a>
            @endif

        
        <a href="/Kravas/jauns">Jauns ieraksts</a>
        <a href="/Klasifikatori" style="border-radius:8px; padding: 5px 10px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff);">Atpakaļ</a>
    </nav>
</div>

<!-- Kravas tabula -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Kravas ID</th>
            <th>Nosaukums</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
        <tr>
            <td>{{$item->KravasID}}</td>
            <td>{{$item->Nosaukums}}</td>
            <td>
                <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                    <a href="/Kravas/{{ $item->KravasID }}/edit" class="btn-action">Rediģēt</a>
                    <a href="/Kravas/{{ $item->KravasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas un pogu stils -->
<style>
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
    padding: 8px; /* samazināts padding, lai tabula kompaktāka */
}

/* Pogas horizontāli ar atstarpēm */
.btn-action {
    border-radius: 8px;
    border: 1px solid #59c1cf;
    padding: 5px 10px;
    color: #000;
    text-decoration: none;
    background-color: #59c1cf;
    white-space: nowrap;
    text-align: center;
}

.btn-action:hover {
    background-color: #a2e0ed;
    color: #000;
}
</style>

@endsection

@if(session('success'))
<div class="alert alert-success" style="margin-top: 10px;">
    {{ session('success') }}
</div>  
@endif
