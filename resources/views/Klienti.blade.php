@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
    <h2>Klienti</h2> <!-- Lapas virsraksts -->

    <!-- Navigācijas poga jauna klienta pievienošanai -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Klienti/jauns">Jauns ieraksts</a>
    </nav>
</div>

<!-- Klientu saraksta tabula -->
<table class="table table-striped" style="width:100%; border:1px solid #59c1cf; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
        <tr>
            <th>Klienta ID</th>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>E-pasts</th>
            <th>Telefona numurs</th>
            <th>Uzņēmuma nosaukums</th>
            <th>Juridiska adrese</th>
            <th>Registrācijas numurs</th>
            <th>Konta numurs</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($klientis as $item)
        <tr>
            <td>{{$item->KlientaID}}</td>
            <td>{{$item->Vards}}</td>
            <td>{{$item->Uzvards}}</td>
            <td>{{$item->Epasts}}</td>
            <td>{{$item->TelefonaNumurs}}</td>
            <td>{{$item->UznemumaNosaukums}}</td>
            <td>{{$item->JuridiskaAdrese}}</td>
            <td>{{$item->RegistracijasNumurs}}</td>
            <td>{{$item->KontaNumurs}}</td>
            <td>


            

    <!-- Pogas izvietotas flex konteinerā ar nelielu gap -->
    <div style="display: flex; justify-content: center; gap: 8px;">
        <a href="/Klienti/{{ $item->KlientaID }}/edit"
           style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf;"
           class="btn btn-sm btn-warning">
            Rediģēt
        </a>
        <a href="/Klienti/{{ $item->KlientaID }}/delete"
           onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
           style="border-radius:8px; border:1px solid #59c1cf; padding:5px 10px; color:#000; text-decoration:none; background-color:#59c1cf;">
            Dzēst
        </a>
    </div>
</td>





            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas stils -->
<style>
    .table {
        border-collapse: collapse;
    }

    .table thead {
        background-color: #59c1cf;
        color: white;
    }

    .table thead th {
        border: 1px solid #59c1cf;
        padding: 12px;
        font-weight: bold;
    }

    .table tbody tr:hover {
        background-color: #e8f5f7;
    }

    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px;
    }
</style>

@endsection

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>  
@endif
