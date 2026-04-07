@extends('layout.app')

@section('content')

<div class="page-klienti">

@php
    $vards = $vards ?? request('vards');
    $uzvards = $uzvards ?? request('uzvards');
    $uznemumanos = $uznemumanos ?? request('uznemumanos');
    $sortBy = $sortBy ?? request('sort_by', 'KlientaID');
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

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
    <h2>Klienti</h2>

    <!-- Lapas galvene -->
    <nav class="navigacija" style="background-color: #ffffff;">
        <a href="/Klienti/jauns">Izveidot klientu</a>
    </nav>
</div>

<!-- Meklēšanas logs -->
<form method="GET" action="/Klienti" class="klienti-filter-form" style="padding: 8px 10px;">
    <div class="filter-window" style="width: fit-content; max-width: 100%;">
        <h4>Filtrēšana</h4>
        <div class="filter-row" style="display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto;">
            <input type="text" name="vards" value="{{ $vards }}" placeholder="Vārds" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
            <input type="text" name="uzvards" value="{{ $uzvards }}" placeholder="Uzvārds" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
            <input type="text" name="uznemumanos" value="{{ $uznemumanos }}" placeholder="Uzņēmuma nosaukums" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <button type="submit" class="filter-btn" style="padding: 2px 8px;">Filtrēt</button>
            <a href="/Klienti" class="filter-btn" style="padding: 2px 8px;">Notīrīt</a>
        </div>
    </div>
</form>

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

<!-- Klientu saraksts -->
<table class="table table-striped" style="width:100%; border:1px solid #C2CBD1; border-radius:8px; overflow:hidden; text-align:center;">
    <thead>
         <tr>
            <th>
                <a href="{{ getSortUrl('Vards', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'Vards' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vārds
                    @if($sortBy == 'Vards')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('Uzvards', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'Uzvards' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Uzvārds
                    @if($sortBy == 'Uzvards')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('Epasts', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'Epasts' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    E-pasts
                    @if($sortBy == 'Epasts')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('TelefonaNumurs', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'TelefonaNumurs' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Telefona numurs
                    @if($sortBy == 'TelefonaNumurs')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('UznemumaNosaukums', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'UznemumaNosaukums' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Uzņēmuma nosaukums
                    @if($sortBy == 'UznemumaNosaukums')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('JuridiskaAdrese', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'JuridiskaAdrese' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Juridiska adrese
                    @if($sortBy == 'JuridiskaAdrese')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('RegistracijasNumurs', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'RegistracijasNumurs' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Reģistrācijas numurs
                    @if($sortBy == 'RegistracijasNumurs')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('KontaNumurs', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'KontaNumurs' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Konta numurs
                    @if($sortBy == 'KontaNumurs')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>Darbības</th>
         </tr>
    </thead>
    <tbody>
        @foreach ($klientis as $item)
         <tr>
            <td>{{$item->Vards}}</td>
            <td>{{$item->Uzvards}}</td>
            <td>{{$item->Epasts}}</td>
            <td>{{$item->TelefonaNumurs}}</td>
            <td>{{$item->UznemumaNosaukums}}</td>
            <td>{{$item->JuridiskaAdrese}}</td>
            <td>{{$item->RegistracijasNumurs}}</td>
            <td>{{$item->KontaNumurs}}</td>
            <td>
                <div style="display: flex; justify-content: center; gap: 8px;">
                    <a href="/Klienti/{{ $item->KlientaID }}/edit"
                       style="border-radius:8px; border:1px solid #C2CBD1; padding:5px 10px; color:#000; text-decoration:none; background-color:#C2CBD1;"
                       class="btn btn-sm btn-warning">
                        Rediģēt
                    </a>
                    <a href="/Klienti/{{ $item->KlientaID }}/delete"
                       onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');"
                       style="border-radius:8px; border:1px solid #991C00; padding:5px 10px; color:#000; text-decoration:none; background-color:#991C00;">
                        Dzēst
                    </a>
                </div>
            </td>
         </tr>
        @endforeach
    </tbody>
</table>

@if ($klientis->hasPages())
<div style="margin-top: 15px; display: flex; justify-content: center;">
    <nav class="klienti-pagination" aria-label="Klientu lapu navigācija">
        <a href="{{ $klientis->onFirstPage() ? '#' : $klientis->previousPageUrl() }}"
           class="page-btn {{ $klientis->onFirstPage() ? 'disabled' : '' }}"
           {{ $klientis->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
            &lsaquo; Iepriekšējā
        </a>

        @foreach ($klientis->getUrlRange(1, $klientis->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn number {{ $page == $klientis->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        <a href="{{ $klientis->hasMorePages() ? $klientis->nextPageUrl() : '#' }}"
           class="page-btn {{ $klientis->hasMorePages() ? '' : 'disabled' }}"
           {{ $klientis->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
            Nākamā &rsaquo;
        </a>
    </nav>
</div>
@endif


</div>

@endsection