@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<h2>Vagonu veidu detalizēta apskate</h2> <!-- Virsraksts lapai -->

<a href="/Veidi" style="border-radius:8px; border: 1px solid #59c1cf; 
    padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
    Atpakaļ
</a> <!-- Poga atpakaļ uz veidu sarakstu -->

<hr> <!-- Horizontālā līnija -->

<div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
    <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;">
        <h5 class="card-title">Veida ID: {{ $veidi->VeidaID }}</h5> <!-- Parāda Veida ID -->
        <p class="card-text"><strong>Nosaukums:</strong> {{ $veidi->Nosaukums }}</p> 
        <p class="card-text"><strong>Celtspeja tonnās:</strong> {{ $veidi->Celtspeja }}</p> 
        <p class="card-text"><strong>Vagonu Skaits:</strong> {{ $veidi->VagonuSkaits }}</p> 
        <p class="card-text"><strong>Cena Par Diennakti:</strong> {{ $veidi->CenaParDiennakti }}</p> 
        <!-- Parāda veida nosaukumu -->
    </div>
</div>

<br><br> <!-- Tukša vieta zem kartes -->

<a href="/Veidi/{{ $veidi->VeidaID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; 
    padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
    Rediģēt
</a> <!-- Poga veida rediģēšanai -->

<a href="/Veidi/{{ $veidi->VeidaID }}/delete" style="border-radius:8px; border: 1px solid #bb6552; 
    padding: 5px; color: #000000; text-decoration: none; background-color: #bb6552;">
    Dzēst
</a> <!-- Poga veida dzēšanai -->

@endsection <!-- Satura sadaļa beidzas -->