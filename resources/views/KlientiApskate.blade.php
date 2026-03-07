@extends('layout.app')

@section('content')
    <h2>Klienta detaļas</h2>
    <a href="/Klienti"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
         <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;">
            <h5 class="card-title">ID: {{ $klientis->KlientaID }}</h5>
            <p class="card-text"><strong>Vārds:</strong> {{ $klientis->Vards }}</p>
            <p class="card-text"><strong>Uzvārds:</strong> {{ $klientis->Uzvards }}</p>
            <p class="card-text"><strong>Parole:</strong> {{ $klientis->Parole }}</p>
            <p class="card-text"><strong>E-pasts:</strong> {{ $klientis->Epasts }}</p>
            <p class="card-text"><strong>Telefona numurs:</strong> {{ $klientis->TelefonaNumurs }}</p>
            <p class="card-text"><strong>Uzņēmuma nosaukums:</strong> {{ $klientis->UznemumaNosaukums }}</p>
            <p class="card-text"><strong>Juridiska adrese:</strong> {{ $klientis->JuridiskaAdrese }}</p>
            <p class="card-text"><strong>Registrācijas numurs:</strong> {{ $klientis->RegistracijasNumurs }}</p>
            <p class="card-text"><strong>Konta numurs:</strong> {{ $klientis->KontaNumurs }}</p>
            
            
        </div>
        
    </div>
       <br><br>
    <a href="/Klienti/{{ $klientis->KlientaID }}/edit"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a>
            <a href="/Klienti/{{ $klientis->KlientaID }}/delete"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a>
@endsection
