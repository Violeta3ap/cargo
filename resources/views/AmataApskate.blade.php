@extends('layout.app') <!-- Paplašina galveno izkārtojuma failu -->

@section('content') <!-- Saturu daļa, kas tiks ielādēta layout -->

    <h2>Amata detalizēta apskate</h2> <!-- Virsraksts -->

    <!-- Poga atpakaļ uz amata sarakstu -->
    <a href="/Amati" style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Atpakaļ
    </a>

    <hr> <!-- Atstarpes līnija -->

    <!-- Kartītes stils ar amata datiem -->
    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
        <div class="card-body" style="padding-left: 50px; padding-top: 5px; padding-bottom: 5px;">
            <!-- Amata ID -->
            <h5 class="card-title">Amata ID: {{ $amati->AmataID }}</h5>

            <!-- Amata nosaukums -->
            <p class="card-text"><strong>Nosaukums:</strong> {{ $amati->Nosaukums }}</p>
        </div>
    </div>

    <br><br> <!-- Atstarpes -->

    <!-- Poga amata rediģēšanai -->
    <a href="/Amati/{{ $amati->AmataID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Rediģēt
    </a>

    <!-- Poga amata dzēšanai -->
    <a href="/Amati/{{ $amati->AmataID }}/delete" style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Dzēst
    </a>

@endsection