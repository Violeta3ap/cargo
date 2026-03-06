@extends('layout.app')

@section('content')
<div style="display: flex">
<h2>Vagona raksturojuma dati</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
    <a href="/">Atpakaļ uz mājas lapu</a>
<a href="/VagonaRaksturojums/jauns" >Jauns ieraksts</a>
</nav>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Veida nosaukums</th>
            <th>Kravas nosaukums</th>
            <th>Celtspēja</th>
            <th>Vagona numurs</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
        <tr>
            <td>{{$item->VagonaID}}</td>
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID) }}</td>
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID) }}</td>
            <td>{{$item->Celtspeja}}</td>
            <td>{{$item->VagonaNumurs}}</td>
            <td>



                <a href="/VagonaRaksturojums/{{ $item->VagonaID }}/details" style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;">Detalizēta</a>
                <a href="/VagonaRaksturojums/{{ $item->VagonaID }}/edit"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a>
                <a href="/VagonaRaksturojums/{{ $item->VagonaID }}/delete"style="border-radius:8px;  border: 1px solid #59c1cf; 
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
