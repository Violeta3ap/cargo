@extends('layout.app')

@section('content')
<div style="display: flex">
<h2>Kravas dati</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
<a href="/Kravas/jauns" >Jauns ieraksts</a>
<a href="/Klasifikatori"  style="border-radius:8px; 
padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>
</nav>
</div>

<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
  <tr>
            <th>Kravas ID</th>
            <th>Nosaukums</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
        <tr>
            <td>{{$item->KravasID}}</td>
            <td>{{$item->Nosaukums}}</td>
            <td>
                <a href="/Kravas/{{ $item->KravasID }}/edit"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a>


                <a href="/Kravas/{{ $item->KravasID }}/delete"
                onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                Dzēst
                </a>


                <!-- <a href="/Kravas/{{ $item->KravasID }}/delete"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-danger">Dzēst</a> -->

            
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