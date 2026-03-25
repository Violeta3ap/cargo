@extends('layout.app') 

@section('content') 
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Vagonu veidi</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">

        @if(Auth::check() && !Auth::user()->isKlients())
            <a href="/Veidi/jauns">Izveidot jaunu vagona veidu</a>
        @endif


    </nav>
</div>


<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>

        <tr>
            <th>Vagona tipa nosaukums</th> 
            <th>Celtspeja tonnās</th> 
            <th>Vagonu Skaits</th> 
            <th>Cena Par Diennakti</th> 
            @if(Auth::check() && !Auth::user()->isKlients())
                <th>Darbības</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item) 
        <tr>
            <td>{{$item->Nosaukums}}</td> 
            <td>{{$item->Celtspeja}}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->CenaParDiennakti}}</td>
            @if(Auth::check() && !Auth::user()->isKlients())
                <td>
                    <a href="/Veidi/{{ $item->VeidaID }}/edit" style="border-radius:8px; border: 1px solid #59c1cf;
                     padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a>

                    <a href="/Veidi/{{ $item->VeidaID }}/delete"
                       onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                       style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;">
                       Dzēst
                    </a>
                </td>
            @endif
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

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
