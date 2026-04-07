@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->

<h2>Darbinieka detalizēta apskate</h2> <!-- Virsraksts -->

<!-- Poga atpakaļ uz darbinieku sarakstu -->
<a href="/Darbinieki"
   style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Atstarpes līnija -->

<!-- Darbinieka detaļu karte -->
<div class="card" style="background:#59c1cf; color:white; width:400px; border-radius:15px;">
    <div class="card-body" style="padding-left:50px; padding-top:5px; padding-bottom:5px;">
        <h5 class="card-title">Darbinieka ID: {{ $darbinieki->DarbiniekaID }}</h5> <!-- Darbinieka ID -->
        <p class="card-text"><strong>Vārds:</strong> {{ $darbinieki->Vards }}</p> <!-- Vārds -->
        <p class="card-text"><strong>Uzvārds:</strong> {{ $darbinieki->Uzvards }}</p> <!-- Uzvārds -->
        <p class="card-text"><strong>E-pasts:</strong> {{ $darbinieki->Epasts }}</p> <!-- E-pasts -->
        <p class="card-text"><strong>Telefona numurs:</strong> {{ $darbinieki->TelefonaNumurs }}</p> <!-- Telefona numurs -->
        <p class="card-text"><strong>Amata ID:</strong> {{ $darbinieki->AmataID }}</p> <!-- Amata ID -->
    </div>
</div>

<br><br> <!-- Atstarpe -->

<!-- Rediģēšanas poga -->
<a href="/Darbinieki/{{ $darbinieki->DarbiniekaID }}/edit"
   style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Rediģēt
</a>

<!-- Dzēšanas poga ar apstiprinājumu -->
<a href="/Darbinieki/{{ $darbinieki->DarbiniekaID }}/delete"
   onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
   style="border-radius:8px; border:1px solid #b62100; padding:5px; color:#000; text-decoration:none; background-color:#b62100;">
   Dzēst
</a>

@endsection <!-- Satura beigas -->
