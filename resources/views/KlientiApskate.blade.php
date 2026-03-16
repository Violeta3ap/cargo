@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Klienta detalizēta apskate</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz klientu sarakstu -->
<a href="/Klienti"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Horizontāla līnija -->

<!-- Karte ar klienta datiem -->
<div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
    <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;">
        <h5 class="card-title">Klienta ID: {{ $klientis->KlientaID }}</h5> <!-- Klienta ID -->
        <p class="card-text"><strong>Vārds:</strong> {{ $klientis->Vards }}</p> <!-- Klienta vārds -->
        <p class="card-text"><strong>Uzvārds:</strong> {{ $klientis->Uzvards }}</p> <!-- Klienta uzvārds -->

        <!-- Parole attēlota kā punkti -->
        <p class="card-text"><strong>Parole:</strong> {{ str_repeat('•', strlen($klientis->Parole)) }}</p>  

        <p class="card-text"><strong>E-pasts:</strong> {{ $klientis->Epasts }}</p> <!-- Klienta e-pasts -->
        <p class="card-text"><strong>Telefona numurs:</strong> {{ $klientis->TelefonaNumurs }}</p> <!-- Telefona numurs -->
        <p class="card-text"><strong>Uzņēmuma nosaukums:</strong> {{ $klientis->UznemumaNosaukums }}</p> <!-- Uzņēmuma nosaukums -->
        <p class="card-text"><strong>Juridiska adrese:</strong> {{ $klientis->JuridiskaAdrese }}</p> <!-- Juridiskā adrese -->
        <p class="card-text"><strong>Registrācijas numurs:</strong> {{ $klientis->RegistracijasNumurs }}</p> <!-- Reģistrācijas numurs -->
        <p class="card-text"><strong>Konta numurs:</strong> {{ $klientis->KontaNumurs }}</p> <!-- Konta numurs -->
    </div>
</div>

<br><br> <!-- Atstarpes -->

<!-- Rediģēt poga -->
<a href="/Klienti/{{ $klientis->KlientaID }}/edit"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Rediģēt
</a>

<!-- Dzēst poga ar apstiprinājuma logu -->
<a href="/Klienti/{{ $klientis->KlientaID }}/delete"
   onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Dzēst
</a>

@endsection <!-- Satura sadaļas beigas -->