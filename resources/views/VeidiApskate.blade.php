@extends('layout.app')

@section('content')
    <h2>Veidu detalizēta apskate</h2>
    <a href="/Veidi"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
         <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;">
            <h5 class="card-title">ID: {{ $veidi->VeidaID }}</h5>
            <p class="card-text"><strong>Nosaukums:</strong> {{ $veidi->Nosaukums }}</p>
            
        </div>
        
    </div>
        <br><br>
    <a href="/Veidi/{{ $veidi->VeidaID }}/edit"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a>
            <a href="/Veidi/{{ $veidi->VeidaID }}/delete"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a>
@endsection
