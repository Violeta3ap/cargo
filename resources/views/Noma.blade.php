@extends('layout.app')

@section('content')
<div style="display: flex">
<h2>Vagonu noma</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
    <a href="/">Atpakaļ uz mājas lapu</a>
<a href="/Noma/jauns" >Jauns ieraksts</a>
</nav>
</div>

<table class="table table-striped">
    <thead>
        <tr>
  <tr>

            <th>ID</th>
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
        @foreach ($noma as $item)
        <tr>
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
            <td>


                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                <a href="/Noma/{{ $item->NomasID }}/details" style="border-radius:8px; border: 1px solid #59c1cf;
                    padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">Detalizēta</a>
                <a href="/Noma/{{ $item->NomasID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf;
                    padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;" class="btn btn-sm btn-warning">Rediģēt</a>
                <a href="/Noma/{{ $item->NomasID }}/delete" style="border-radius:8px; border: 1px solid #59c1cf;
                    padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;" class="btn btn-sm btn-danger">Dzēst</a>
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


