@extends('layout.app')

@section('content')
    <h2>Nomas detalizēta apskate</h2>
    <a href="/Noma"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>
<button onclick="window.print()" 
style="border-radius:8px; border:1px solid #59c1cf; padding:5px; background: linear-gradient(to right, #59c1cf, #ffffff);">
Printēt
</button>
    <hr>

    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
         <div class="card-body" style="padding-left: 50px;padding-top: 5px;padding-bottom: 5px;">
            <h5 class="card-title">ID: {{ $noma->NomasID }}</h5>
            <p class="card-text"><strong>Klienta vārds:</strong> {{ $noma->Vards }}</p>
            <p class="card-text"><strong>Klienta uzvārds:</strong> {{ $noma->Uzvards }}</p>
            <p class="card-text"><strong>Klienta uzņēmuma nosaukums:</strong> {{ $noma->UznemumaNosaukums }}</p>
            <p class="card-text"><strong>Darbinieka vārds:</strong> {{ $noma->Vards }}</p>
            <p class="card-text"><strong>Darbinieka uzvārds:</strong> {{ $noma->Uzvards }}</p>
            <p class="card-text"><strong>Kravas nosaukums:</strong> {{ $noma->Nosaukums }}</p>
            <p class="card-text"><strong>Vagonu skaits:</strong> {{ $noma->VagonuSkaits }}</p>
            <p class="card-text"><strong>Nomas sākuma periods:</strong> {{ $noma->NomasSakumaPeriods }}</p>
            <p class="card-text"><strong>Nomas beigu periods:</strong> {{ $noma->NomasBeiguPeriods }}</p>
            <p class="card-text"><strong>Nosūtīšanas stacija:</strong> {{ $noma->NosutisanasStacija }}</p>
            <p class="card-text"><strong>Galastacija:</strong> {{ $noma->Galastacija }}</p>
            <p class="card-text"><strong>Kopēja maksa:</strong> {{ $noma->KopejaMaksa }}</p>
            
            
        </div>
        
    </div>
       <br><br>
    <a href="/Noma/{{ $noma->NomasID }}/edit"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a>
            <a href="/Noma/{{ $noma->NomasID }}/delete"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a>
@endsection



