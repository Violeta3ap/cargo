@extends('layout.app')

@section('content')

@php
    $search = $search ?? request('search', '');
    $sortBy = $sortBy ?? request('sort_by', 'KravasID');
    $sortOrder = $sortOrder ?? request('sort_order', 'asc');
    
    // Palīgfunkcija kārtošanas URL ģenerēšanai
    function getSortUrl($field, $currentSortBy, $currentSortOrder, $params) {
        if ($currentSortBy == $field) {
            $newOrder = $currentSortOrder == 'asc' ? 'desc' : 'asc';
        } else {
            $newOrder = 'asc';
        }
        
        $params['sort_by'] = $field;
        $params['sort_order'] = $newOrder;
        
        return '?' . http_build_query($params);
    }
@endphp

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Krāvu veidi</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">

        @if(Auth::check() && !Auth::user()->isKlients())
            <a href="/Kravas/jauns">Izveidot jaunu kravas veidu</a>
        @endif
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-top: 10px;">
        {{ session('success') }}
    </div>  
@endif

@if(session('error'))
    <div class="alert alert-danger" style="margin-top: 10px;">
        {{ session('error') }}
    </div>
@endif

<!-- Meklēšanas logs -->
<form method="GET" action="/Kravas" class="veidi-search-form" style="margin-bottom: 15px;">
    <div class="search-window" style="border: 1px solid #59c1cf; border-radius: 10px; padding: 10px; background: #f8fdfe;">
        <div class="search-row" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Meklēt pēc kravas veida..." style="flex: 1; border: 1px solid #59c1cf; border-radius: 8px; padding: 8px 10px;">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <button type="submit" class="filter-btn" style="padding: 8px 15px;">Meklēt</button>
            <a href="/Kravas" class="filter-btn" style="padding: 8px 15px;">Notīrīt</a>
        </div>
    </div>
</form>

<!-- Kravas tabula -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
         <tr>
            <th>
                <a href="{{ getSortUrl('Nosaukums', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'Nosaukums' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Kravas veids
                    @if($sortBy == 'Nosaukums')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('VeidaID', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'VeidaID' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagona nosaukums
                    @if($sortBy == 'VeidaID')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            @if(Auth::check() && !Auth::user()->isKlients())
                <th>Darbības</th>
            @endif
         </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
         <tr>
            <td>{{$item->Nosaukums}}</td>
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID) }}</td>
            @if(Auth::check() && !Auth::user()->isKlients())
                <td>
                    <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                        <a href="/Kravas/{{ $item->KravasID }}/edit" class="btn-action">Rediģēt</a>
                        <a href="/Kravas/{{ $item->KravasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                    </div>
                </td>
            @endif
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
    position: relative;
}

.table thead th a.sort-link {
    color: white;
    text-decoration: none;
    display: inline-block;
    padding: 5px;
    transition: opacity 0.2s ease;
}

.table thead th a.sort-link:hover {
    opacity: 0.8;
}

.table thead th .sort-icon {
    display: inline-block;
    margin-left: 5px;
    font-size: 12px;
}

.table tbody tr:hover {
    background-color: #e8f5f7;
}

.table tbody td {
    border: 1px solid #ddd;
    padding: 8px; /* samazināts padding, lai tabula kompaktāka */
}

.veidi-search-form input {
    border: 1px solid #59c1cf;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 0.92rem;
}

.filter-btn {
    flex: 0 0 auto;
    padding: 4px 12px;
    font-size: 0.8rem;
    border-radius: 6px;
    border: 1px solid #59c1cf;
    background-color: #59c1cf;
    color: #000;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
}

.filter-btn:hover {
    background-color: #a2e0ed;
}

/* Pogas horizontāli ar atstarpēm */
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

.alert {
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
</style>

@endsection