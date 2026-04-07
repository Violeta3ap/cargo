@extends('layout.app')

@section('content')

<div class="page-kravas">

@php
    $search = $search ?? request('search', '');
    $vagonaId = $vagonaId ?? request('vagona_id', '');
    $kravasOptions = $kravasOptions ?? collect();
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

        @if(Auth::check() && Auth::user()->isAdmin())
            <a href="/Kravas/jauns">Izveidot kravas veidu</a>
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
<form method="GET" action="/Kravas" class="veidi-search-form" id="kravas-search-form" style="margin-bottom: 15px; padding: 8px 10px;">
    <div class="search-window" style="border: 1px solid #C2CBD1; border-radius: 10px; padding: 10px; background: #f8fdfe; width: fit-content; max-width: 100%;">
        <h4>Filtrēšana</h4>
        <div class="search-row" style="display: flex; gap: 8px; align-items: center; overflow-x: auto;">
            <select name="search" data-live-search="true" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 240px; background-color: #fff;">
                <option value="">Kravas nosaukums</option>
                @foreach($kravasOptions as $option)
                    <option value="{{ $option }}" {{ $search === $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <input type="text" name="vagona_id" value="{{ $vagonaId }}" placeholder="VagonaID" data-live-search="true" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 140px;">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <button type="submit" class="filter-btn" style="padding: 2px 8px;">Filtrēt</button>
            <a href="/Kravas" class="filter-btn" style="padding: 2px 8px;">Notīrīt</a>
        </div>
    </div>
</form>

<div id="kravas-results">
<!-- Kravas tabula -->
<table class="table table-striped" style="width: 100%; border: 1px solid #C2CBD1; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
         <tr>
            <th>
                <a href="{{ getSortUrl('Nosaukums', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search, 'vagona_id' => $vagonaId])) }}" 
                   class="sort-link {{ $sortBy == 'Nosaukums' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Kravas veids
                    @if($sortBy == 'Nosaukums')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('VeidaID', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search, 'vagona_id' => $vagonaId])) }}" 
                   class="sort-link {{ $sortBy == 'VeidaID' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagona nosaukums
                    @if($sortBy == 'VeidaID')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            @if(Auth::check() && Auth::user()->isAdmin())
                <th>Darbības</th>
            @endif
         </tr>
    </thead>
    <tbody>
        @foreach ($dati as $item)
         <tr>
            <td>{{$item->Nosaukums}}</td>
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID) }}</td>
            @if(Auth::check() && Auth::user()->isAdmin())
                <td>
                    <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                        <a href="/Kravas/{{ $item->KravasID }}/edit" class="btn-action">Rediģēt</a>
                        <a href="/Kravas/{{ $item->KravasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action" style="border-color:#b62100; background-color:#b62100; color:#fff;">Dzēst</a>
                    </div>
                </td>
            @endif
         </tr>
        @endforeach
    </tbody>
</table>

@if ($dati->hasPages())
<div style="margin-top: 15px; display: flex; justify-content: center;">
    <nav class="kravas-pagination" aria-label="Kravu lapu navigācija" style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap; justify-content: center;">
        <a href="{{ $dati->onFirstPage() ? '#' : $dati->previousPageUrl() }}"
           style="border-radius: 8px; border: 1px solid #C2CBD1; padding: 6px 12px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff); white-space: nowrap; font-size: 0.92rem; line-height: 1; {{ $dati->onFirstPage() ? 'opacity:0.45; pointer-events:none;' : '' }}"
           {{ $dati->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
            &lsaquo; Iepriekšējā
        </a>

        @foreach ($dati->getUrlRange(1, $dati->lastPage()) as $page => $url)
            <a href="{{ $url }}"
               style="border-radius: 8px; border: 1px solid #C2CBD1; min-width: 34px; text-align: center; padding: 6px 10px; color: #000000; text-decoration: none; background: {{ $page == $dati->currentPage() ? '#C2CBD1' : 'linear-gradient(to right, #C2CBD1, #ffffff)' }}; font-weight: {{ $page == $dati->currentPage() ? '600' : '400' }};">
                {{ $page }}
            </a>
        @endforeach

        <a href="{{ $dati->hasMorePages() ? $dati->nextPageUrl() : '#' }}"
           style="border-radius: 8px; border: 1px solid #C2CBD1; padding: 6px 12px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff); white-space: nowrap; font-size: 0.92rem; line-height: 1; {{ $dati->hasMorePages() ? '' : 'opacity:0.45; pointer-events:none;' }}"
           {{ $dati->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
            Nākamā &rsaquo;
        </a>
    </nav>
</div>
@endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('kravas-search-form');
    const resultsContainer = document.getElementById('kravas-results');

    if (!form || !resultsContainer) {
        return;
    }

    const liveInputs = form.querySelectorAll('[data-live-search="true"]');
    let debounceTimer;
    let activeRequest;

    const runLiveSearch = function () {
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
                const freshResults = parsedDoc.getElementById('kravas-results');

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

    liveInputs.forEach(function (input) {
        const eventName = input.tagName === 'SELECT' ? 'change' : 'input';

        input.addEventListener(eventName, function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                runLiveSearch();
            }, 300);
        });
    });
});
</script>

</div>

@endsection