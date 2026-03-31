@extends('layout.app') 

@section('content') 

<div class="page-veidi">

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
            <a href="/Veidi/jauns">Izveidot vagona veidu</a>
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
<form method="GET" action="/Veidi" class="veidi-search-form" style="margin-bottom: 15px; padding: 8px 10px;">
    <div class="filter-window" style="width: fit-content; max-width: 100%;">
        <h4>Filtrēšana</h4>
        <div class="filter-row" style="display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Meklēt pēc vagona veida..." style="border: 1px solid #59c1cf; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 240px;">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <button type="submit" class="filter-btn" style="padding: 2px 8px;">Filtrēt</button>
            <a href="/Veidi" class="filter-btn" style="padding: 2px 8px;">Notīrīt</a>
        </div>
    </div>
</form>

<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>
                <a href="{{ getSortUrl('Nosaukums', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'Nosaukums' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagona nosaukums
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
                    Vagonu skaits
                    @if($sortBy == 'VagonuSkaits')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th> 
            <th>
                <a href="{{ getSortUrl('CenaParDiennakti', $sortBy, $sortOrder, array_merge(request()->except(['page']), ['search' => $search])) }}" 
                   class="sort-link {{ $sortBy == 'CenaParDiennakti' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Cena par diennakti
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
            <td>{{ number_format($item->CenaParDiennakti, 2) }} €</td>
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
        @empty
          <tr>
            <td colspan="{{ Auth::check() && !Auth::user()->isKlients() ? 5 : 4 }}" style="text-align: center; padding: 20px;">
                Nav atrasts neviens vagona veids
            </td>
          </tr>
        @endforelse
    </tbody>
</table>

@if ($dati->hasPages())
<div style="margin-top: 15px; display: flex; justify-content: center;">
    <nav class="veidi-pagination" aria-label="Veidu lapu navigācija" style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap; justify-content: center;">
        <a href="{{ $dati->onFirstPage() ? '#' : $dati->previousPageUrl() }}"
           style="border-radius: 8px; border: 1px solid #59c1cf; padding: 6px 12px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff); white-space: nowrap; font-size: 0.92rem; line-height: 1; {{ $dati->onFirstPage() ? 'opacity:0.45; pointer-events:none;' : '' }}"
           {{ $dati->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
            &lsaquo; Iepriekšējā
        </a>

        @foreach ($dati->getUrlRange(1, $dati->lastPage()) as $page => $url)
            <a href="{{ $url }}"
               style="border-radius: 8px; border: 1px solid #59c1cf; min-width: 34px; text-align: center; padding: 6px 10px; color: #000000; text-decoration: none; background: {{ $page == $dati->currentPage() ? '#59c1cf' : 'linear-gradient(to right, #59c1cf, #ffffff)' }}; font-weight: {{ $page == $dati->currentPage() ? '600' : '400' }};">
                {{ $page }}
            </a>
        @endforeach

        <a href="{{ $dati->hasMorePages() ? $dati->nextPageUrl() : '#' }}"
           style="border-radius: 8px; border: 1px solid #59c1cf; padding: 6px 12px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff); white-space: nowrap; font-size: 0.92rem; line-height: 1; {{ $dati->hasMorePages() ? '' : 'opacity:0.45; pointer-events:none;' }}"
           {{ $dati->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
            Nākamā &rsaquo;
        </a>
    </nav>
</div>
@endif

</div>

@endsection