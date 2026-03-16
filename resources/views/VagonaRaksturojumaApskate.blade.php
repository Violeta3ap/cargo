@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->
    <h2>Vagonu datu detalizēta apskate</h2> <!-- Virsraksts -->
    <a href="/VagonaRaksturojums"  style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a> <!-- Atpakaļ poga -->

    <hr> <!-- Horizontāla līnija -->

    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;"> <!-- Kartītes konteiners -->
         <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;"> <!-- Kartītes saturs -->
            <h5 class="card-title">Vagona ID: {{ $vagonaraksturojums->VagonaID }}</h5> <!-- Vagona ID -->
            <p class="card-text"><strong>Veida ID:</strong> {{ $vagonaraksturojums->VeidaID }}</p> <!-- Veida ID -->
            <p class="card-text"><strong>Kravas ID:</strong> {{ $vagonaraksturojums->KravasID }}</p> <!-- Kravas ID -->
            <p class="card-text"><strong>Celtspeja:</strong> {{ $vagonaraksturojums->Celtspeja }}</p> <!-- Celtspeja -->
            <p class="card-text"><strong>Vagona numurs:</strong> {{ $vagonaraksturojums->VagonaNumurs }}</p> <!-- Vagona numurs -->
        </div>
    </div>
       <br><br> <!-- Atstarpes -->

    <a href="/VagonaRaksturojums/{{ $vagonaraksturojums->VagonaID }}/edit"  style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a> <!-- Rediģēt poga -->
    <a href="/VagonaRaksturojums/{{ $vagonaraksturojums->VagonaID }}/delete"  style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a> <!-- Dzēst poga -->

@endsection <!-- Satura sadaļa beidzas -->