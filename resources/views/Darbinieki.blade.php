@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->

<div style="display: flex">
    <h2>Darbinieki</h2> <!-- Virsraksts -->

    <!-- Navigācijas poga jauna darbinieka pievienošanai -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Darbinieki/jauns">Jauns ieraksts</a>
    </nav>
</div>

<!-- Darbinieku saraksta tabula -->
<table class="table table-striped" style="width:100%; border:1px solid #59c1cf; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
        <tr>
            <th>Darbinieka ID</th> <!-- Kolonna ID -->
            <th>Vārds</th> <!-- Kolonna vārds -->
            <th>Uzvārds</th> <!-- Kolonna uzvārds -->
            <th>Parole</th> <!-- Kolonna parole -->
            <th>E-pasts</th> <!-- Kolonna e-pasts -->
            <th>Telefona numurs</th> <!-- Kolonna telefona numurs -->
            <th>Amata nosaukums</th> <!-- Kolonna amata nosaukums -->
            <th>Darbības</th> <!-- Kolonna darbībām -->
        </tr>
    </thead>
    <tbody>
        @foreach ($darbiniekis as $item) <!-- Cikls cauri visiem darbiniekiem -->
        <tr>
            <td>{{$item->DarbiniekaID}}</td> <!-- Darbinieka ID -->
            <td>{{$item->Vards}}</td> <!-- Vārds -->
            <td>{{$item->Uzvards}}</td> <!-- Uzvārds -->
            <td>{{$item->Parole}}</td> <!-- Parole -->
            <td>{{$item->Epasts}}</td> <!-- E-pasts -->
            <td>{{$item->TelefonaNumurs}}</td> <!-- Telefona numurs -->
            <td>{{$item->amati->Nosaukums ?? ('ID: '.$item->AmataID) }}</td> <!-- Amata nosaukums vai ID, ja nosaukums nav -->
            <td>
                <!-- Rediģēšanas poga -->
                <a href="/Darbinieki/{{ $item->DarbiniekaID }}/edit"
                   style="border-radius:8px; border:1px solid #59c1cf; padding: 5px 10px; color:#000; text-decoration:none; background-color:#59c1cf;"
                   class="btn btn-sm btn-warning">
                    Rediģēt
                </a>

                <!-- Dzēšanas poga ar apstiprinājumu -->
                <a href="/Darbinieki/{{ $item->DarbiniekaID }}/delete"
                   onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                   style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf; white-space:nowrap;">
                    Dzēst
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas stils -->
<style>
    .table {
        border-collapse: collapse; /* Tabulas apmales saplūst */
    }
    
    .table thead {
        background-color: #59c1cf; /* Galvenes fons */
        color: white; /* Teksta krāsa */
    }
    
    .table thead th {
        border: 1px solid #59c1cf;
        padding: 12px;
        font-weight: bold;
    }
    
    .table tbody tr:hover {
        background-color: #e8f5f7; /* Hover efekts rindām */
    }
    
    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px;
    }
</style>

@endsection

<!-- Paziņojums par veiksmīgu darbību -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }} <!-- Rāda sesijas ziņojumu -->
    </div>  
@endif