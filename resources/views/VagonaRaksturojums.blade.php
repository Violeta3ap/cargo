@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<div style="display: flex">
    <h2>Vagona raksturojuma dati</h2> <!-- Virsraksts -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/VagonaRaksturojums">Jauns ieraksts</a> <!-- Poga jauna vagonu ieraksta pievienošanai -->
        <a href="/Klasifikatori" style="border-radius:8px; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
            Atpakaļ
        </a> <!-- Atpakaļ poga uz klasifikatoriem -->
    </nav>
</div>

<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Vagona ID</th> <!-- Kolonnas nosaukums -->
            <th>Veida nosaukums</th>
            <th>Kravas nosaukums</th>
            <th>Celtspēja</th>
            <th>Vagona numurs</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item) <!-- Cikls cauri visiem vagonu ierakstiem -->
        <tr>
            <td>{{$item->VagonaID}}</td> <!-- Vagona ID -->
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID) }}</td> <!-- Veida nosaukums vai ID, ja nav saistīts ieraksts -->
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID) }}</td> <!-- Kravas nosaukums vai ID -->
            <td>{{$item->Celtspeja}}</td> <!-- Vagona celtspeja -->
            <td>{{$item->VagonaNumurs}}</td> <!-- Vagona numurs -->
            <td>
                <a href="/VagonaRaksturojums/{{ $item->VagonaID }}/edit"
                    style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" 
                    class="btn btn-sm btn-warning">Rediģēt</a> <!-- Rediģēt poga -->

                <a href="/VagonaRaksturojums/{{ $item->VagonaID }}/delete"
                    onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                    style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                    Dzēst
                </a> <!-- Dzēst poga ar apstiprinājumu -->

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<style>
    .table { border-collapse: collapse; } <!-- Apvieno rāmjus -->
    .table thead { background-color: #59c1cf; color: white; } <!-- Galvenes stils -->
    .table thead th { border: 1px solid #59c1cf; padding: 12px; font-weight: bold; } <!-- Galvenes šūnu stils -->
    .table tbody tr:hover { background-color: #e8f5f7; } <!-- Hover efekts rindām -->
    .table tbody td { border: 1px solid #ddd; padding: 10px; } <!-- Šūnu stils -->
</style>

@endsection <!-- Satura sadaļa beidzas -->

@if(session('success')) <!-- Paziņojums par veiksmīgu darbību -->
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif