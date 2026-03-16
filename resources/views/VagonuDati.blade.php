@extends('layout.app')

@section('content')
<div style="display: flex">
<h2>Nomas papildinājums</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
<a href="/VagonuDati/jauns" >Jauns ieraksts</a>
</nav>
</div>

<table class="table table-striped">
    <thead>
        <tr>
  <tr>
            <th>Datu ID</th>
            <th>NomasID</th>
            <th>VagonaID</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
        <tr>
            <td>{{$item->DatuID}}</td>
            <td>{{$item->NomasID}}</td>
            <td>{{$item->VagonaID}}</td>
            <td>
                <a href="/VagonuDati/{{ $item->DatuID }}/edit"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a>


                <a href="/VagonuDati/{{ $item->DatuID }}/delete"
                onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                Dzēst
                </a>



                <!-- <a href="/VagonuDati/{{ $item->DatuID }}/delete"style="border-radius:8px;  border: 1px solid #59c1cf; 
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
