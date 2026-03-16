@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<!-- Virsraksts un navigācija -->
<div style="display: flex">
    <h2>Noma</h2> <!-- Lapas virsraksts -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Noma/jauns">Jauns ieraksts</a> <!-- Poga jaunam ierakstam -->
        <a href="/VagonuDati">Nomas papildinājums</a> <!-- Poga vagonu datiem -->
    </nav>
</div>

<!-- Nomas tabula -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Nomas ID</th>
            <th>Klienta vārds</th>
            <th>Klienta uzvārds</th>
            <th>Klienta uzņēmuma nosaukums</th>
            <th>Darbinieka vārds</th>
            <th>Darbinieka uzvārds</th>
            <th>Kravas nosaukums</th>
            <th>Vagonu skaits</th>
            <th>Nomas sākuma periods</th>
            <th>Nomas beigu periods</th>
            <th>Nosutīšanas stacija</th>
            <th>Galastacija</th>
            <th>Kopēja maksa</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($noma as $item) <!-- Cikls visām nomas rindām -->
        <tr>
            <!-- Datu kolonnas ar fallback, ja saistītie ieraksti nav pieejami -->
            <td>{{$item->NomasID}}</td>
            <td>{{$item->klienti->Vards ?? ('ID: '.$item->KlientaID) }}</td>
            <td>{{$item->klienti->Uzvards ?? ('ID: '.$item->KlientaID) }}</td>
            <td>{{$item->klienti->UznemumaNosaukums ?? ('ID: '.$item->KlientaID) }}</td>
            <td>{{$item->darbinieki->Vards ?? ('ID: '.$item->DarbiniekaID) }}</td>
            <td>{{$item->darbinieki->Uzvards ?? ('ID: '.$item->DarbiniekaID) }}</td>
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID) }}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->NomasSakumaPeriods}}</td>
            <td>{{$item->NomasBeiguPeriods}}</td>
            <td>{{$item->NosutisanasStacija}}</td>
            <td>{{$item->Galastacija}}</td>
            <td>{{$item->KopejaMaksa}}</td>

            <!-- Darbību pogas -->
            <td>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    <a href="/Noma/{{ $item->NomasID }}/details" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                        Detalizēta
                    </a>
                    <a href="/Noma/{{ $item->NomasID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;" class="btn btn-sm btn-warning">
                        Rediģēt
                    </a>
                    <a href="/Noma/{{ $item->NomasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                        Dzēst
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas stili -->
<style>
    .table {
        border-collapse: collapse; /* Apvieno robežas */
    }
    
    .table thead {
        background-color: #59c1cf; /* Galvenes fons */
        color: white; /* Teksts balts */
    }
    
    .table thead th {
        border: 1px solid #59c1cf;
        padding: 12px;
        font-weight: bold; /* Trekns teksts galvenē */
    }
    
    .table tbody tr:hover {
        background-color: #e8f5f7; /* Hover efekts */
    }
    
    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px; /* Iekšējais polsterējums */
    }
</style>

@endsection <!-- Satura sadaļas beigas -->

<!-- Paziņojums par veiksmīgu darbību -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif