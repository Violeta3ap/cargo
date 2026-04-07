@extends('layout.app')

@section('content')

<div class="page-klienti">

@php
    $vards = $vards ?? request('vards');
    $uzvards = $uzvards ?? request('uzvards');
    $uznemumanos = $uznemumanos ?? request('uznemumanos');
    $search = $search ?? request('search');
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

<!-- Filtrēšanas logs un meklēšana -->
<form method="GET" action="/Klienti" class="klienti-filter-form" id="klienti-filter-form" style="padding: 8px 10px;">
    <div style="display: flex; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
        <div class="filter-window" style="width: fit-content; max-width: 100%;">
            <h4>Filtrēšana</h4>
            <div class="filter-row" style="display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto;">
                <input type="text" name="vards" value="{{ $vards }}" placeholder="Vārds" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
                <input type="text" name="uzvards" value="{{ $uzvards }}" placeholder="Uzvārds" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
                <input type="text" name="uznemumanos" value="{{ $uznemumanos }}" placeholder="Uzņēmuma nosaukums" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
                <button type="submit" class="filter-btn" style="padding: 2px 8px;">Filtrēt</button>
                <a href="{{ '?' . http_build_query(request()->except(['vards', 'uzvards', 'uznemumanos', 'page'])) }}" class="filter-btn" style="padding: 2px 8px;">Notīrīt filtrus</a>
            </div>
        </div>

        <div class="filter-window" style="width: fit-content; max-width: 100%;">
            <h4>Meklēšana</h4>
            <div class="filter-row" style="display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Meklēt klientu" data-live-search="true" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 220px;">
                <a href="{{ '?' . http_build_query(request()->except(['search', 'page'])) }}" class="filter-btn" style="padding: 2px 8px;">Notīrīt meklēšanu</a>
            </div>
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

<div id="klienti-results">
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
                               style="border-radius:8px; border:1px solid #b62100; padding:5px 10px; color:#fff; text-decoration:none; background-color:#b62100;">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('klienti-filter-form');
    const resultsContainer = document.getElementById('klienti-results');

    if (!form || !resultsContainer) {
        return;
    }

    const searchInput = form.querySelector('input[data-live-search="true"]');
    let debounceTimer;
    let activeRequest;

    if (!searchInput) {
        return;
    }

    const runLiveSearch = function () {
        const valueLength = searchInput.value.trim().length;

        if (valueLength < 1 && valueLength !== 0) {
            return;
        }

        const params = new URLSearchParams(new FormData(form));
        params.delete('page');
        const url = form.action + '?' + params.toString();

        if (activeRequest) {
            activeRequest.abort();
        }

        activeRequest = new AbortController();

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: activeRequest.signal
        })
            .then(function (response) {
                return response.text();
            })
            .then(function (html) {
                const parsedDoc = new DOMParser().parseFromString(html, 'text/html');
                const freshResults = parsedDoc.getElementById('klienti-results');

                if (!freshResults) {
                    return;
                }

                resultsContainer.innerHTML = freshResults.innerHTML;
                window.history.replaceState({}, '', url);
            })
            .catch(function (error) {
                if (error.name !== 'AbortError') {
                    window.location.href = url;
                }
            });
    };

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            runLiveSearch();
        }, 300);
    });
});
</script>


</div>

@endsection