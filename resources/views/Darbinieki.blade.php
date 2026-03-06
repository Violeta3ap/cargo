@extends('layout.app')

@section('content')
<div style="display: flex">
<h2>Darbinieki</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
    <a href="/">Atpakaļ uz mājas lapu</a>
<a href="/Darbinieki/jauns" >Jauns ieraksts</a>
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
            <th>Amata nosaukums</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($darbiniekis as $item)
        <tr>
            <td>{{$item->DarbiniekaID}}</td>
            <td>{{$item->Vards}}</td>
            <td>{{$item->Uzvards}}</td>
            <td>{{$item->Parole}}</td>
            <td>{{$item->Epasts}}</td>
            <td>{{$item->TelefonaNumurs}}</td>
            <td>{{$item->amati->nosaukums ?? ('ID: '.$item->AmataID) }}</td>
            <td>



                <a href="/Darbinieki/{{ $item->DarbiniekaID }}/details" style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;">Detalizēta</a>
                <a href="/Darbinieki/{{ $item->DarbiniekaID }}/edit"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a>
                <a href="/Darbinieki/{{ $item->DarbiniekaID }}/delete"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-danger">Dzēst</a>

            
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
