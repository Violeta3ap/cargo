@extends('layout.app')

@section('content')
    <h2>Darbinieka detaļas</h2>
    <a href="/Darbinieki"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $darbinieki->DarbiniekaID }}</h5>
            <p class="card-text"><strong>Vārds:</strong> {{ $darbinieki->Vards }}</p>
            <p class="card-text"><strong>Uzvārds:</strong> {{ $darbinieki->Uzvards }}</p>
            <p class="card-text"><strong>Parole:</strong> {{ $darbinieki->Parole }}</p>
            <p class="card-text"><strong>E-pasts:</strong> {{ $darbinieki->Epasts }}</p>
            <p class="card-text"><strong>Telefona numurs:</strong> {{ $darbinieki->TelefonaNumurs }}</p>
            <p class="card-text"><strong>Amata ID:</strong> {{ $darbinieki->AmataID }}</p>
            
            
        </div>
        
    </div>
    <a href="/Darbinieki/{{ $darbinieki->DarbiniekaID }}/edit"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a>
            <a href="/Darbinieki/{{ $darbinieki->DarbiniekaID }}/delete"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a>
@endsection
