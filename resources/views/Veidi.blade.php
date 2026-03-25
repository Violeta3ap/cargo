@extends('layout.app') 

@section('content') 

@php
    $search = $search ?? request('search', '');
    $sortBy = $sortBy ?? request('sort_by', 'VeidaID');
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
    <h2>Vagonu veidi</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        @if(Auth::check() && !Auth::user()->isKlients())
            <a href="/Veidi/jauns">Izveidot jaunu vagona veidu</a>
        @endif
    </nav>
</div>

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

<!-- Meklēšanas logs -->
<form method="GET" action="/Veidi" class="veidi-search-form" style="margin-bottom: 15px;">
    <div class="search-window" style="border: 1px solid #59c1cf; border-radius: 10px; padding: 10px; background: #f8fdfe;">
        <div class="search-row" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Meklēt pēc vagona veida..." style="flex: 1; border: 1px solid #59c1cf; border-radius: 8px; padding: 8px 10px;">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <button type="submit" class="filter-btn" style="padding: 8px 15px;">Meklēt</button>
            <a href="/Veidi" class="filter-btn" style="padding: 8px 15px;">Notīrīt</a>
        </div>
    </div>
</form>



<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>
                <a href="{{ getSortUrl('Nosaukums', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'Nosaukums' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagona tipa nosaukums
                    @if($sortBy == 'Nosaukums')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th> 
            <th>
                <a href="{{ getSortUrl('Celtspeja', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'Celtspeja' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Celtspēja tonnās
                    @if($sortBy == 'Celtspeja')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th> 
            <th>
                <a href="{{ getSortUrl('VagonuSkaits', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'VagonuSkaits' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagonu Skaits
                    @if($sortBy == 'VagonuSkaits')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th> 
            <th>
                <a href="{{ getSortUrl('CenaParDiennakti', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'CenaParDiennakti' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Cena Par Diennakti
                    @if($sortBy == 'CenaParDiennakti')
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
        @forelse ($dati as $item) 
          <tr>
            <td>{{$item->Nosaukums}}</td> 
            <td>{{$item->Celtspeja}} t</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>€ {{ number_format($item->CenaParDiennakti, 2) }}</td>
            @if(Auth::check() && !Auth::user()->isKlients())
                <td>
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <a href="/Veidi/{{ $item->VeidaID }}/edit" 
                           style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf;" 
                           class="btn-action">
                            Rediģēt
                        </a>

                        <a href="/Veidi/{{ $item->VeidaID }}/delete"
                           onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                           style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px 10px; color: #000000; text-decoration: none; background-color: #59c1cf; white-space: nowrap;"
                           class="btn-action">
                           Dzēst
                        </a>
                    </div>
                </td>
            @endif
          </tr>
       
    </tbody>
</table>

<style>
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
        padding: 10px; 
    }
    
    .btn-action {
        transition: background-color 0.2s ease;
    }
    
    .btn-action:hover {
        background-color: #a2e0ed !important;
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