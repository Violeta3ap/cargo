@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<div style="display: flex">
    <h2>Nomas papildinājums</h2> <!-- Virsraksts -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/VagonuDati/jauns">Jauns ieraksts</a> <!-- Poga jauna vagonu ieraksta pievienošanai -->
        <a href="/Klasifikatori" style="border-radius:8px; padding:5px; color:#000; text-decoration:none; background:linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a> <!-- Poga atpakaļ uz klasifikatoriem -->
    </nav>
</div>

<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Datu ID</th> <!-- Kolonnas nosaukums -->
            <th>NomasID</th>
            <th>VagonaID</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item) <!-- Cikls cauri visiem ierakstiem -->
        <tr>
            <td>{{$item->DatuID}}</td> <!-- Ieraksta ID -->
            <td>{{$item->NomasID}}</td> <!-- Saistītās nomas ID -->
            <td>{{$item->VagonaID}}</td> <!-- Saistītā vagona ID -->
            <td>
                <a href="/VagonuDati/{{ $item->DatuID }}/edit"
                    style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;"
                    class="btn btn-sm btn-warning">Rediģēt</a> <!-- Rediģēt poga -->

                <a href="/VagonuDati/{{ $item->DatuID }}/delete"
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
    .table { border-collapse: collapse; } 
    .table thead { background-color: #59c1cf; color: white; } 
    .table thead th { border: 1px solid #59c1cf; padding: 12px; font-weight: bold; } 
    .table tbody tr:hover { background-color: #e8f5f7; } 
    .table tbody td { border: 1px solid #ddd; padding: 10px; } 
</style>

@endsection <!-- Satura sadaļa beidzas -->

@if(session('success')) <!-- Paziņojums par veiksmīgu darbību -->
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif