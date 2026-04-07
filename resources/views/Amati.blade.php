@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Amata dati</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Amati/jauns" style="margin-right: 10px;">Jauns ieraksts</a>
        <a href="/Klasifikatori" style="border-radius:8px; padding: 5px 10px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff);">
            Atpakaļ
        </a>
    </nav>
</div>

<!-- Amatu saraksta tabula -->
<table class="table table-striped" style="width:100%; border:1px solid #59c1cf; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
        <tr>
            <th>Amata ID</th> 
            <th>Nosaukums</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
        <tr>
            <td>{{$item->AmataID}}</td>
            <td>{{$item->Nosaukums}}</td>
            <td>
                <div style="display: flex; gap: 10px; justify-content: center;"> <!-- Horizontāli ar atstarpēm -->
                    <!-- Rediģēšanas poga -->
                    <a href="/Amati/{{ $item->AmataID }}/edit" 
                       style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf;"
                       class="btn btn-sm btn-warning">
                        Rediģēt
                    </a>

                    <!-- Dzēšanas poga ar apstiprinājumu -->
                    <a href="/Amati/{{ $item->AmataID }}/delete"
                       onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                              style="border-radius:8px; border:1px solid #b62100; padding:5px 10px; color:#000; text-decoration:none; background-color:#b62100; white-space:nowrap;">
                        Dzēst
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas stils -->
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
        padding: 10px;
    }
</style>

@endsection

<!-- Paziņojums par veiksmīgu darbību -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif