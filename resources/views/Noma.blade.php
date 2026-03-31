@extends('layout.app')

@section('content')

<div class="page-noma">

@php
    $klientaVards = $klientaVards ?? request('klienta_vards');
    $klientaUzvards = $klientaUzvards ?? request('klienta_uzvards');
    $klientaUznemums = $klientaUznemums ?? request('klienta_uznemums');
    $filtraUznemums = $filtraUznemums ?? request('filtra_uznemums');
    $krava = $krava ?? request('krava');
    $veids = $veids ?? request('veids');
    $nomasSakumaPeriods = $nomasSakumaPeriods ?? request('nomas_sakuma_periods');
    $nomasBeiguPeriods = $nomasBeiguPeriods ?? request('nomas_beigu_periods');
    $sortBy = $sortBy ?? request('sort_by', 'NomasID');
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

<!-- Lapas galvene -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Noma</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noslogojums">Noslogojums</a>
        <a href="/Noma/jauns">Izveidot nomas pieteikumu</a>
        <a type="button" onclick="window.print()" title="Printēt dokumentu" class="print-btn"><i class="fas fa-print"></i> Drukāt</a>
    </nav>
</div>


<!-- Meklēšanas un filtrēšanas logi -->
<form method="GET" action="/Noma" class="noma-filter-form" style="margin-bottom: 15px; padding: 8px 10px;">
    <div class="filter-window">
        <h4>Filtrēšana</h4>
        <div class="filter-row">
            @if(!(Auth::check() && Auth::user()->isKlients()))
                <input type="text" name="filtra_uznemums" value="{{ $filtraUznemums }}" placeholder="Klienta uzņēmums">
            @endif
            <input type="text" name="krava" value="{{ $krava }}" placeholder="Kravas nosaukums">
            <input type="text" name="veids" value="{{ $veids }}" placeholder="Vagona tips">
            <input type="text" class="datepicker" name="nomas_sakuma_periods" value="{{ $nomasSakumaPeriods }}" title="Nomas sākuma periods" placeholder="Nomas sākuma periods" autocomplete="off">
            <input type="text" class="datepicker" name="nomas_beigu_periods" value="{{ $nomasBeiguPeriods }}" title="Nomas beigu periods" placeholder="Nomas beigu periods" autocomplete="off">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <button type="submit" class="filter-btn">Filtrēt</button>
        </div>
    </div>

    @if(!(Auth::check() && Auth::user()->isKlients()))
        <div class="filter-window">
            <h4>Meklēšana</h4>
            <div class="filter-row">
                <input type="text" name="klienta_vards" value="{{ $klientaVards }}" placeholder="Klienta vārds">
                <input type="text" name="klienta_uzvards" value="{{ $klientaUzvards }}" placeholder="Klienta uzvārds">
                <input type="text" name="klienta_uznemums" value="{{ $klientaUznemums }}" placeholder="Klienta uzņēmums">
                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
                <button type="submit" class="filter-btn">Meklēt</button>
            </div>
        </div>
    @endif
    <a href="/Noma" class="filter-btn">Notīrīt</a>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === 'undefined') {
        return;
    }

    flatpickr('.datepicker', {
        locale: 'lv',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: false
    });
});
</script>

@if(session('success'))
    <div class="alert alert-success" style="margin-top: 10px;">
        {{ session('success') }}
    </div>  
@endif

<!-- Nomas saraksts -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
         <tr>
            <th>
                <a href="{{ getSortUrl('KlientaID', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'KlientaID' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Klients
                    @if($sortBy == 'KlientaID')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>Klienta uzņēmums</th>
            <th>
                <a href="{{ getSortUrl('KravasID', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'KravasID' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Kravas veids
                    @if($sortBy == 'KravasID')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('VeidaID', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'VeidaID' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagona nosaukums
                    @if($sortBy == 'VeidaID')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('VagonuSkaits', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'VagonuSkaits' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Vagonu skaits
                    @if($sortBy == 'VagonuSkaits')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('NomasSakumaPeriods', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'NomasSakumaPeriods' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Nomas sākuma periods
                    @if($sortBy == 'NomasSakumaPeriods')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('NomasBeiguPeriods', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'NomasBeiguPeriods' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Nomas beigu periods
                    @if($sortBy == 'NomasBeiguPeriods')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('KopejaMaksa', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'KopejaMaksa' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Kopējā maksa
                    @if($sortBy == 'KopejaMaksa')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>Darbības</th>
         </tr>
    </thead>
    <tbody>
        @foreach ($noma as $item)
         <tr>
            <td>{{$item->klienti->Vards ?? ('ID: '.$item->KlientaID)}} {{$item->klienti->Uzvards ?? ''}}</td>
            <td>{{$item->klienti->UznemumaNosaukums ?? ('ID: '.$item->KlientaID)}}</td>
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID)}}</td>
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID)}}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->NomasSakumaPeriods}}</td>
            <td>{{$item->NomasBeiguPeriods}}</td>
            <td>{{ number_format($item->KopejaMaksa, 2) }} €</td>
            <td>
                <div style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                    <a href="/Noma/{{ $item->NomasID }}/edit" class="btn-action">Rediģēt</a>
                    <a href="/Noma/{{ $item->NomasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                </div>
            </td>
          </tr>
        @endforeach
    </tbody>
</table>

@if ($noma->hasPages())
<div style="margin-top: 15px; display: flex; justify-content: center;">
    <nav class="noma-pagination" aria-label="Nomas lapu navigācija">
        <a href="{{ $noma->onFirstPage() ? '#' : $noma->previousPageUrl() }}"
           class="page-btn {{ $noma->onFirstPage() ? 'disabled' : '' }}"
           {{ $noma->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
            &lsaquo; Iepriekšējā
        </a>

        @foreach ($noma->getUrlRange(1, $noma->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn number {{ $page == $noma->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        <a href="{{ $noma->hasMorePages() ? $noma->nextPageUrl() : '#' }}"
           class="page-btn {{ $noma->hasMorePages() ? '' : 'disabled' }}"
           {{ $noma->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
            Nākamā &rsaquo;
        </a>
    </nav>
</div>
@endif

</div>

@endsection
