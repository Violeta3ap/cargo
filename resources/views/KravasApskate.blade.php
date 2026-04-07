@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Kravas detalizēta apskate</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz kravu sarakstu -->
<a href="/Kravas"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Horizontāla līnija -->

<!-- Karte ar kravas datiem -->
<div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
    <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;">
        <h5 class="card-title">Kravas ID: {{ $kravas->KravasID }}</h5> <!-- Kravas ID -->
        <p class="card-text"><strong>Nosaukums:</strong> {{ $kravas->Nosaukums }}</p> <!-- Kravas nosaukums -->
    </div>
</div>

<br><br> <!-- Atstarpes -->

<!-- Rediģēt poga -->
<a href="/Kravas/{{ $kravas->KravasID }}/edit"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Rediģēt
</a>

<!-- Dzēst poga -->
<a href="/Kravas/{{ $kravas->KravasID }}/delete"  
   style="border-radius:8px; border: 1px solid #b62100; padding: 5px; color: #000000; text-decoration: none; background-color: #b62100;">
   Dzēst
</a>

@endsection <!-- Satura sadaļas beigas -->