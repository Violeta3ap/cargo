@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<div style="display: flex">
    <h2>Kravas dati</h2> <!-- Lapas virsraksts -->

    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Kravas/jauns">Jauns ieraksts</a> <!-- Poga jaunas kravas pievienošanai -->
        <a href="/Klasifikatori"  
           style="border-radius:8px; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
           Atpakaļ
        </a> <!-- Atpakaļ poga uz klasifikatoriem -->
    </nav>
</div>

<!-- Tabula ar kravas datiem -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Kravas ID</th> <!-- Kravas ID -->
            <th>Nosaukums</th> <!-- Kravas nosaukums -->
            <th>Darbības</th> <!-- Poga Rediģēt / Dzēst -->
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item) <!-- Cikls cauri visām kravām -->
        <tr>
            <td>{{$item->KravasID}}</td> <!-- Kravas ID -->
            <td>{{$item->Nosaukums}}</td> <!-- Kravas nosaukums -->
            <td>
                <!-- Rediģēt poga -->
                <a href="/Kravas/{{ $item->KravasID }}/edit"
                   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" 
                   class="btn btn-sm btn-warning">
                   Rediģēt
                </a>

                <!-- Dzēst poga ar apstiprinājuma logu -->
                <a href="/Kravas/{{ $item->KravasID }}/delete"
                   onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                   Dzēst
                </a>

                
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- CSS stili tabulai -->
<style>
.table {
    border-collapse: collapse; /* Šūnu robežu salikšana */
}

.table thead {
    background-color: #59c1cf; /* Galvenes fona krāsa */
    color: white; /* Galvenes teksta krāsa */
}

.table thead th {
    border: 1px solid #59c1cf; /* Galvenes šūnu robeža */
    padding: 12px; /* Šūnu iekšējais attālums */
    font-weight: bold; /* Treknraksts */
}

.table tbody tr:hover {
    background-color: #e8f5f7; /* Rinda maina krāsu, kad peles kursors virs tās */
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