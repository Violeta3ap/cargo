@extends('layout.app')

@section('content')
    <h2>Kravas detalizēta apskate</h2>
    <a href="/Kravas"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $kravas->KravasID }}</h5>
            <p class="card-text"><strong>Nosaukums:</strong> {{ $kravas->Nosaukums }}</p>
            
        </div>
        
    </div>
    <a href="/Kravas/{{ $kravas->KravasID }}/edit"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a>
            <a href="/Kravas/{{ $kravas->KravasID }}/delete"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a>
@endsection
