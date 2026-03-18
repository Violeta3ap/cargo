@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Nomas detalizēta apskate</h2> <!-- Lapas virsraksts -->
<a href="/Noma" style="border-radius:8px; border: 1px solid #59c1cf; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
    Atpakaļ
</a> <!-- Poga atpakaļ uz nomas sarakstu -->

<hr> <!-- Horizontāla līnija -->

<!-- Kartes (card) izkārtojums nomas detaļām -->
<div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
    <div class="card-body" style="padding-left: 50px; padding-top: 5px; padding-bottom: 5px;">
        <h5 class="card-title">Nomas ID: {{ $noma->NomasID }}</h5> <!-- Nomas ID -->
        <p class="card-text"><strong>Klienta vārds:</strong> {{ $noma->klienti->Vards }}</p>
        <p class="card-text"><strong>Klienta uzvārds:</strong> {{ $noma->klienti->Uzvards }}</p>
        <p class="card-text"><strong>Klienta uzņēmuma nosaukums:</strong> {{ $noma->klienti->UznemumaNosaukums }}</p>
        <!-- <p class="card-text"><strong>Darbinieka vārds:</strong> {{ $noma->darbinieki->Vards }}</p>
        <p class="card-text"><strong>Darbinieka uzvārds:</strong> {{ $noma->darbinieki->Uzvards }}</p> -->
        <p class="card-text"><strong>Kravas nosaukums:</strong> {{ $noma->kravas->Nosaukums }}</p>
        <p class="card-text"><strong>Svars tonnās:</strong> {{ $noma->Svars }}</p>
        <p class="card-text"><strong>Vagona veida nosaukums:</strong> {{ $noma->veidi->Nosaukums }}</p>
        <p class="card-text"><strong>Vagonu skaits:</strong> {{ $noma->Skaits }}</p>
        <p class="card-text"><strong>Nomas sākuma periods:</strong> {{ $noma->NomasSakumaPeriods }}</p>
        <p class="card-text"><strong>Nomas beigu periods:</strong> {{ $noma->NomasBeiguPeriods }}</p>
        <!-- <p class="card-text"><strong>Nosūtīšanas stacija:</strong> {{ $noma->NosutisanasStacija }}</p>
        <p class="card-text"><strong>Galastacija:</strong> {{ $noma->Galastacija }}</p> -->
        <p class="card-text"><strong>Kopēja maksa:</strong> {{ $noma->KopejaMaksa }}</p>
    </div>
</div>

<br><br> <!-- Atstarpe pirms pogām -->

<a href="/Noma/{{ $noma->NomasID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
    Rediģēt
</a> <!-- Poga nomas datu rediģēšanai -->



@endsection <!-- Satura sadaļas beigas -->