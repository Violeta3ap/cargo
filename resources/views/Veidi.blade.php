@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<div style="display: flex">
<h2>Vagona veidi</h2> <!-- Virsraksts lapai -->

<nav class="navigacija" style="background-color: #ffffff;">
    <a href="/Veidi/jauns">Jauns ieraksts</a> <!-- Poga jauna veida pievienošanai -->
    <a href="/Klasifikatori" style="border-radius:8px; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a> <!-- Poga atpakaļ uz klasifikatoriem -->
</nav>
</div>

<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Veida ID</th> <!-- Kolonnas virsraksts ID -->
            <th>Nosaukums</th> <!-- Kolonnas virsraksts nosaukums -->
            <th>Darbības</th> <!-- Kolonnas virsraksts darbībām (rediģēt/dzēst) -->
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item) <!-- Cikls cauri visiem vagonu veidiem -->
        <tr>
            <td>{{$item->VeidaID}}</td> <!-- Parāda Veida ID -->
            <td>{{$item->Nosaukums}}</td> <!-- Parāda veida nosaukumu -->
            <td>
                <a href="/Veidi/{{ $item->VeidaID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a> <!-- Poga ieraksta rediģēšanai -->

                <a href="/Veidi/{{ $item->VeidaID }}/delete" 
                   onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                   Dzēst
                </a> <!-- Poga ieraksta dzēšanai ar apstiprinājumu -->

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

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

@if(session('success')) 
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif