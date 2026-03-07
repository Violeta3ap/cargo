@extends('layout.app')

@section('content')
    <h2>Amata detalizēta apskate</h2>
    <a href="/Amati"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>
<!-- 
    <div class="card" style="background: #59c1cf; color: white; width: 400px; border-radius: 15px;">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $amati->AmataID }}</h5>
            <p class="card-text"><strong>Nosaukums:</strong> {{ $amati->Nosaukums }}</p>
            
        </div> -->



        
<table class="table table-striped">
    <thead>
        <tr>
  <tr>
            <th>ID</th>
            <th>Nosaukums</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($amati as $item)
        <tr>
            <td>{{$amati->AmataID}}</td>
            <td>{{$amati->Nosaukums}}</td>
            </td>




        
    </div>
    <a href="/Amati/{{ $amati->AmataID }}/edit"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Rediģēt</a>
            <a href="/Amati/{{ $amati->AmataID }}/delete"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Dzēst</a>
@endsection
