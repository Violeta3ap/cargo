@extends('layout.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Darbinieki</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Darbinieki/jauns">Jauns ieraksts</a>
    </nav>
</div>

<!-- Darbinieku tabula -->
<table class="table table-striped" style="width:100%; border:1px solid #59c1cf; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
        <tr>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>E-pasts</th>
            <th>Telefona numurs</th>
            <th>Amata nosaukums</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($darbiniekis as $item)
        <tr>
            <td>{{$item->Vards}}</td>
            <td>{{$item->Uzvards}}</td>
            <td>{{$item->Epasts}}</td>
            <td>{{$item->TelefonaNumurs}}</td>
            <td>{{$item->amati->Nosaukums ?? ('ID: '.$item->AmataID) }}</td>
            <td>
                <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                    <a href="/Darbinieki/{{ $item->DarbiniekaID }}/edit" class="btn-action">Rediģēt</a>
                    <a href="/Darbinieki/{{ $item->DarbiniekaID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Tabulas un pogu stils -->
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

    /* Pogas stils horizontāli ar atstarpēm */
    .btn-action {
        border-radius: 8px;
        border: 1px solid #59c1cf;
        padding: 5px 10px;
        color: #000;
        text-decoration: none;
        background-color: #59c1cf;
        white-space: nowrap;
        text-align: center;
    }

    .btn-action:hover {
        background-color: #a2e0ed;
        color: #000;
    }
</style>

@endsection

@if(session('success'))
<div class="alert alert-success" style="margin-top: 10px;">
    {{ session('success') }}
</div>  
@endif
