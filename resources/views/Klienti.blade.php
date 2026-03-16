@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<div style="display: flex">
    <h2>Klienti</h2> <!-- Lapas virsraksts -->
    
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Klienti/jauns">Jauns ieraksts</a> <!-- Poga jauna klienta pievienošanai -->
    </nav>
</div>

<!-- Meklēšanas forma (šobrīd komentēta) -->
<!-- 
<form method="GET" action="/Klienti">
    <input type="text" name="search" placeholder="Ievadi uzņēmuma nosaukumu">
    <button type="submit">Meklēt</button>
</form> -->

<!-- Tabula ar klientu datiem -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Klienta ID</th>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>Parole</th>
            <th>E-pasts</th>
            <th>Telefona numurs</th>
            <th>Uzņēmuma nosaukums</th>
            <th>Juridiska adrese</th>
            <th>Registrācijas numurs</th>
            <th>Konta numurs</th>
            <th>Darbības</th> <!-- Kolonna ar pogām Rediģēt / Dzēst -->
        </tr>
    </thead>
    <tbody>
        @foreach ($klientis as $item) <!-- Cikls cauri visiem klientiem -->
        <tr>
            <td>{{$item->KlientaID}}</td> <!-- Klienta ID -->
            <td>{{$item->Vards}}</td> <!-- Klienta vārds -->
            <td>{{$item->Uzvards}}</td> <!-- Klienta uzvārds -->
            <td>{{$item->Parole}}</td> <!-- Klienta parole -->
            <td>{{$item->Epasts}}</td> <!-- Klienta e-pasts -->
            <td>{{$item->TelefonaNumurs}}</td> <!-- Klienta telefona numurs -->
            <td>{{$item->UznemumaNosaukums}}</td> <!-- Uzņēmuma nosaukums -->
            <td>{{$item->JuridiskaAdrese}}</td> <!-- Juridiskā adrese -->
            <td>{{$item->RegistracijasNumurs}}</td> <!-- Reģistrācijas numurs -->
            <td>{{$item->KontaNumurs}}</td> <!-- Konta numurs -->
            <td>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    <!-- Rediģēt poga -->
                    <a href="/Klienti/{{ $item->KlientaID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;" class="btn btn-sm btn-warning">Rediģēt</a>

                    <!-- Dzēst poga ar apstiprinājuma logu -->
                    <a href="/Klienti/{{ $item->KlientaID }}/delete"
                       onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                       style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                       Dzēst
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- CSS stili tabulai -->
<style>
    .table {
        border-collapse: collapse; /* Saliek šūnu robežas kopā */
    }
    
    .table thead {
        background-color: #59c1cf; /* Galvenes fona krāsa */
        color: white; /* Galvenes teksta krāsa */
    }
    
    .table thead th {
        border: 1px solid #59c1cf; /* Galvenes šūnu robežas */
        padding: 12px; /* Šūnu iekšējais attālums */
        font-weight: bold; /* Treknraksts */
    }
    
    .table tbody tr:hover {
        background-color: #e8f5f7; /* Rinda maina krāsu uz pelēku, kad peles kursors virs tās */
    }
    
    .table tbody td {
        border: 1px solid #ddd; /* Šūnu robeža */
        padding: 10px; /* Šūnu iekšējais attālums */
    }
</style>

@endsection <!-- Satura sadaļas beigas -->

<!-- Paziņojums par veiksmīgu darbību -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }} <!-- Attēlo session mainīgo 'success' -->
    </div>  
@endif