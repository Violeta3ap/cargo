@extends('layout.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Noma</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noma/jauns">Jauns ieraksts</a>
        <a href="/VagonuDati">Nomas papildinājums</a>
    </nav>
</div>

<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Nomas ID</th>
            <th>Klients</th>
            <th>Darbinieks</th>
            <th>Krava</th>
            <th>Vagonu skaits</th>
            <th>Nomas periods</th>
            <th>Stacijas</th>
            <th>Kopēja maksa</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($noma as $item)
        <tr>
            <td>{{$item->NomasID}}</td>
            <td>{{$item->klienti->Vards ?? ''}} {{$item->klienti->Uzvards ?? ''}}</td>
            <td>{{$item->darbinieki->Vards ?? ''}} {{$item->darbinieki->Uzvards ?? ''}}</td>
            <td>{{$item->kravas->Nosaukums ?? ''}}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->NomasSakumaPeriods}} - {{$item->NomasBeiguPeriods}}</td>
            <td>{{$item->NosutisanasStacija}} → {{$item->Galastacija}}</td>
            <td>{{$item->KopejaMaksa}}</td>
            <td>
                <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                    <a href="/Noma/{{ $item->NomasID }}/details" class="btn-action">Detalizēta</a>
                    <a href="/Noma/{{ $item->NomasID }}/edit" class="btn-action">Rediģēt</a>
                    <a href="/Noma/{{ $item->NomasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Paginācijas saites -->
<div style="margin-top: 15px; display: flex; justify-content: center;">
    {{ $noma->links() }}
</div>

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
    padding: 8px;
}
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