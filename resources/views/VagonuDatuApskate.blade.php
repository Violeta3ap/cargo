@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<h2>Vagonu datu detalizēta apskate</h2> <!-- Virsraksts lapai -->

<a href="/VagonuDati"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a> <!-- Poga, lai atgrieztos pie saraksta -->

<hr> <!-- Horizontāla līnija, lai atdalītu -->

<div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
    <div class="card-body" style="padding-left: 50px; padding-top: 5px; padding-bottom: 5px;">
        <h5 class="card-title">Datu ID: {{ $vagonudati->DatuID }}</h5> <!-- Ieraksta ID -->
        <p class="card-text"><strong>Nomas ID:</strong> {{ $vagonudati->NomasID }}</p> <!-- Saistītās nomas ID -->
        <p class="card-text"><strong>Vagona ID:</strong> {{ $vagonudati->VagonaID }}</p> <!-- Saistītā vagona ID -->
    </div>
</div>

<br><br> <!-- Atstarpes pēc kartes -->

<a href="/VagonuDati/{{ $vagonudati->DatuID }}/edit"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Rediģēt
</a> <!-- Poga, lai rediģētu ierakstu -->

<a href="/VagonuDati/{{ $vagonudati->DatuID }}/delete"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Dzēst
</a> <!-- Poga, lai dzēstu ierakstu -->

@endsection <!-- Satura sadaļa beidzas -->