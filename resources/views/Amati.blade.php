@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->

<div style="display: flex">
    <h2>Amata dati</h2> <!-- Virsraksts -->

    <!-- Navigācijas pogas -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Amati">Jauns ieraksts</a> <!-- Poga jauna amata pievienošanai -->
        <a href="/Klasifikatori" style="border-radius:8px; padding:5px; color:#000; text-decoration:none; background:linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a> <!-- Poga atpakaļ uz klasifikatoriem -->
    </nav>
</div>

<!-- Amatu saraksta tabula -->
<table class="table table-striped" style="width:100%; border:1px solid #59c1cf; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
        <tr>
            <th>Amata ID</th> <!-- Kolonna ID -->
            <th>Nosaukums</th> <!-- Kolonna nosaukums -->
            <th>Darbības</th> <!-- Kolonna darbībām -->
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item) <!-- Cikls cauri visiem amata ierakstiem -->
        <tr>
            <td>{{$item->AmataID}}</td> <!-- Amata ID -->
            <td>{{$item->Nosaukums}}</td> <!-- Amata nosaukums -->
            <td>
                <!-- Rediģēšanas poga -->
                <a href="/Amati/{{ $item->AmataID }}/edit" style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background-color:#59c1cf;" class="btn btn-sm btn-warning">
                    Rediģēt
                </a>

                <!-- Dzēšanas poga ar apstiprinājumu -->
                <a href="/Amati/{{ $item->AmataID }}/delete"
                   onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                   style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf; white-space:nowrap;">
                    Dzēst
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas stils -->
<style>
    .table {
        border-collapse: collapse; /* Tabulas apmales saplūst */
    }
    
    .table thead {
        background-color: #59c1cf; /* Galvenes fons */
        color: white; /* Teksta krāsa */
    }
    
    .table thead th {
        border: 1px solid #59c1cf;
        padding: 12px;
        font-weight: bold;
    }
    
    .table tbody tr:hover {
        background-color: #e8f5f7; /* Hover efekts */
    }
    
    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px;
    }
</style>

@endsection

<!-- Paziņojums par veiksmīgu darbību -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }} <!-- Rāda sesijas ziņojumu -->
    </div>  
@endif