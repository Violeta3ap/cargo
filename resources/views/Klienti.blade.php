@extends('layout.app')

@section('content')
<div style="display: flex">
<h2>Klienti</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
    <a href="/">Atpakaļ uz mājas lapu</a>
<a href="/Klienti/jauns" >Jauns ieraksts</a>
</nav>
</div>

<table class="table table-striped">
    <thead>
        <tr>

  <tr>
            <th>ID</th>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>Parole</th>
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
            <td>{{$item->Parole}}</td>
            <td>{{$item->Epasts}}</td>
            <td>{{$item->TelefonaNumurs}}</td>
            <td>{{$item->UznemumaNosaukums}}</td>
            <td>{{$item->JuridiskaAdrese}}</td>
            <td>{{$item->RegistracijasNumurs}}</td>
            <td>{{$item->KontaNumurs}}</td>
            <td>


            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                <a href="/Klienti/{{ $item->KlientaID }}/details" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">Detalizēta</a>
                <a href="/Klienti/{{ $item->KlientaID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;" class="btn btn-sm btn-warning">Rediģēt</a>
                <a href="/Klienti/{{ $item->KlientaID }}/delete" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;" class="btn btn-sm btn-danger">Dzēst</a>
            </div>

            
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
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