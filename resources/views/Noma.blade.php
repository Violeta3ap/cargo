@extends('layout.app')

@section('content')

<div class="page-noma">

@php
    $klientaVards = $klientaVards ?? request('klienta_vards');
    $klientaUzvards = $klientaUzvards ?? request('klienta_uzvards');
    $klientaUznemums = $klientaUznemums ?? request('klienta_uznemums');
    $mekleKlientaUznemums = $mekleKlientaUznemums ?? request('mekle_klienta_uznemums');
    $mekleKrava = $mekleKrava ?? request('mekle_krava');
    $mekleVeids = $mekleVeids ?? request('mekle_veids');
    $filtraUznemums = $filtraUznemums ?? request('filtra_uznemums');
    $krava = $krava ?? request('krava');
    $veids = $veids ?? request('veids');
    $nomasStatuss = $nomasStatuss ?? request('nomas_statuss');
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


<div class="noma-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Noma</h2>
    <nav class="navigacija noma-subnav" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noslogojums">Noslogojums</a>
        <a href="/Noma/arhivs">Nomas arhīvs</a>
        @if(Auth::check() && Auth::user()->isKlients())
            <a href="/Noma/jauns">Izveidot nomas pieteikumu</a>
        @endif
        <a type="button" onclick="window.print()" title="Printēt dokumentu" class="print-btn"><i class="fas fa-print"></i> Drukāt</a>
    </nav>
</div>



<form method="GET" action="/Noma" class="noma-filter-form" style="margin-bottom: 15px; padding: 8px 10px;">
    <div class="filter-window" style="width: fit-content; max-width: 100%;">
        <h4>Filtrēšana</h4>
        <div class="filter-row" style="display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto;">
            @if(Auth::check() && Auth::user()->isAdmin())
                <input type="text" name="klienta_vards" value="{{ $klientaVards }}" placeholder="Klienta vārds" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
                <input type="text" name="klienta_uzvards" value="{{ $klientaUzvards }}" placeholder="Klienta uzvārds" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
                <input type="text" name="klienta_uznemums" value="{{ $klientaUznemums }}" placeholder="Klienta uzņēmums" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
            @endif
            <select name="krava" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px; background-color: #fff;">
                <option value="">Kravas nosaukums</option>
                @foreach($kravaOptions as $option)
                    <option value="{{ $option }}" {{ $krava === $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <select name="veids" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px; background-color: #fff;">
                <option value="">Vagona veids</option>
                @foreach($veidaOptions as $option)
                    <option value="{{ $option }}" {{ $veids === $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <select name="nomas_statuss" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px; background-color: #fff;">
                <option value="">Nomas statuss</option>
                @foreach($nomasStatusaOptions as $option)
                    <option value="{{ $option }}" {{ $nomasStatuss === $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <input type="text" class="datepicker" name="nomas_sakuma_periods" value="{{ $nomasSakumaPeriods }}" title="Nomas sākuma periods" placeholder="Nomas sākuma periods" autocomplete="off" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
            <input type="text" class="datepicker" name="nomas_beigu_periods" value="{{ $nomasBeiguPeriods }}" title="Nomas beigu periods" placeholder="Nomas beigu periods" autocomplete="off" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <input type="hidden" name="mekle_klienta_uznemums" value="{{ $mekleKlientaUznemums }}">
            <input type="hidden" name="mekle_krava" value="{{ $mekleKrava }}">
            <input type="hidden" name="mekle_veids" value="{{ $mekleVeids }}">
            <button type="submit" class="filter-btn" style="padding: 2px 8px;">Filtrēt</button>
            <a href="/Noma" class="filter-btn" style="padding: 2px 8px;">Notīrīt</a>
        </div>
    </div>

</form>

<form method="GET" action="/Noma" class="noma-search-form" id="noma-search-form" style="margin-bottom: 15px; padding: 8px 10px;">
    <div class="filter-window" style="width: fit-content; max-width: 100%;">
        <h4>Meklēšana</h4>
        <div class="filter-row" style="display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto;">
            @if(Auth::check() && Auth::user()->isAdmin())
                <input type="text" name="mekle_klienta_uznemums" value="{{ $mekleKlientaUznemums }}" placeholder="Klienta uzņēmums" data-live-search="true" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 220px;">
            @endif
            <input type="text" name="mekle_krava" value="{{ $mekleKrava }}" placeholder="Kravas nosaukums" data-live-search="true" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 220px;">
            <input type="text" name="mekle_veids" value="{{ $mekleVeids }}" placeholder="Vagona veids" data-live-search="true" style="border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px; font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 220px;">

            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <input type="hidden" name="klienta_vards" value="{{ $klientaVards }}">
            <input type="hidden" name="klienta_uzvards" value="{{ $klientaUzvards }}">
            <input type="hidden" name="klienta_uznemums" value="{{ $klientaUznemums }}">
            <input type="hidden" name="krava" value="{{ $krava }}">
            <input type="hidden" name="veids" value="{{ $veids }}">
            <input type="hidden" name="nomas_statuss" value="{{ $nomasStatuss }}">
            <input type="hidden" name="nomas_sakuma_periods" value="{{ $nomasSakumaPeriods }}">
            <input type="hidden" name="nomas_beigu_periods" value="{{ $nomasBeiguPeriods }}">

            <a href="{{ '?' . http_build_query(request()->except(['mekle_klienta_uznemums', 'mekle_krava', 'mekle_veids', 'page'])) }}" class="filter-btn" style="padding: 2px 8px;">Notīrīt meklēšanu</a>
        </div>
    </div>
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

    // Pievieno drukāšanas datumu galvenei
    const nomaHeader = document.querySelector('.page-noma .noma-header');
    if (nomaHeader) {
        const now = new Date();
        const formatted = now.getDate().toString().padStart(2,'0') + '.' +
            (now.getMonth()+1).toString().padStart(2,'0') + '.' + now.getFullYear() +
            ' ' + now.getHours().toString().padStart(2,'0') + ':' +
            now.getMinutes().toString().padStart(2,'0');
        nomaHeader.setAttribute('data-print-date', formatted);
    }
});
</script>

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


<div id="noma-results">
<div class="noma-table-wrap">
<table class="table table-striped noma-table" style="width: 100%; border: 1px solid #C2CBD1; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
         <tr>
            <th>
                <a href="{{ getSortUrl('NomasID', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'NomasID' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Nomas Nr.
                    @if($sortBy == 'NomasID')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>


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
            <th>
                <a href="{{ getSortUrl('NomasStatuss', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'NomasStatuss' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Nomas statuss
                    @if($sortBy == 'NomasStatuss')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>Atteikuma iemesls</th>
            <th>
                <a href="{{ getSortUrl('MaksasStatuss', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'MaksasStatuss' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Maksas statuss
                    @if($sortBy == 'MaksasStatuss')
                        <span class="sort-icon">{!! $sortOrder == 'asc' ? '↑' : '↓' !!}</span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ getSortUrl('PabeigsanasStatuss', $sortBy, $sortOrder, request()->except(['page'])) }}" 
                   class="sort-link {{ $sortBy == 'PabeigsanasStatuss' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                    Nomas pabeigšanas statuss
                    @if($sortBy == 'PabeigsanasStatuss')
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
            @php
                $nomasStatusaNosaukums = mb_strtolower((string) ($item->nomasStatuss->Nosaukums ?? ''));
                $maksasStatusaNosaukums = mb_strtolower((string) ($item->maksasStatuss->Nosaukums ?? ''));
                $irNoraidits = $nomasStatusaNosaukums === 'noraidīts';
                $irPieteikts = $nomasStatusaNosaukums === 'pieteikts';
                $irNavApmaksats = $maksasStatusaNosaukums === 'nav apmaksāts' || $maksasStatusaNosaukums === 'neapmaksāts';
                
                // Atteikuma iemesls - parāda tekstu tieši, ja statuss ir Noraidīts
                $iemeslaTeksts = trim((string) ($item->AtteikumaIemesls ?? ''));
                $atteikumaTeksts = ($iemeslaTeksts !== '') ? $iemeslaTeksts : 'Iemesls nav norādīts.';
                
                // Pārbauda vai rediģēšanas poga ir pieejama
                $varRediget = ($item->PabeigsanasStatuss ?? null) !== 'Pabeigts' && !$irNoraidits;
            @endphp
            <td>{{$item->NomasID}}</td>
            <td>{{$item->klienti->Vards ?? ('ID: '.$item->KlientaID)}} {{$item->klienti->Uzvards ?? ''}}</td>
            <td>{{$item->klienti->UznemumaNosaukums ?? ('ID: '.$item->KlientaID)}}</td>
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID)}}</td>
            <td>{{$item->veidi->Nosaukums ?? ('ID: '.$item->VeidaID)}}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->NomasSakumaPeriods}}</td>
            <td>{{$item->NomasBeiguPeriods}}</td>
            <td>{{ number_format($item->KopejaMaksa, 2) }} €</td>
            <td>{{ $item->nomasStatuss->Nosaukums ?? 'Pieteikts' }}</td>
            <td>
                {{-- Atteikuma iemesls redzams tieši tabulā tikai tad, ja statuss ir Noraidīts --}}
                @if($irNoraidits)
                    {{ $atteikumaTeksts }}
                @else
                    -
                @endif
            </td>
            <td>{{ $item->maksasStatuss->Nosaukums ?? '-' }}</td>
            <td>
                {{-- Pabeigšanas statuss nerādās, ja ir Pieteikts, Noraidīts vai Nav apmaksāts --}}
                @if($irPieteikts || $irNoraidits || $irNavApmaksats)
                    -
                @else
                    {{ $item->PabeigsanasStatuss ?? '-' }}
                @endif
            </td>
            <td>
                <div style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                    @if($varRediget)
                        <a href="/Noma/{{ $item->NomasID }}/edit" class="btn-action">Rediģēt</a>
                    @endif
                    @if(Auth::check())
                        <a href="/Noma/{{ $item->NomasID }}/delete" class="btn-action js-confirm-action" style="border-color:#b62100; background-color:#b62100; color:#fff;" data-confirm-title="Dzēst nomu?" data-confirm-message="Vai tiešām vēlaties dzēst šo nomu? Ieraksts tiks pārvietots uz arhīvu." data-confirm-button="Jā, dzēst">Dzēst</a>
                    @endif
                </div>
            </td>
          </tr>
        @endforeach
    </tbody>
</table>
</div>

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

<style>
@media print {
    /* Slēpj nevajadzīgos elementus */
    .page-noma .btn-action,
    .page-noma .print-btn,
    .page-noma .noma-subnav,
    .page-noma .noma-filter-form,
    .page-noma .noma-search-form,
    .noma-pagination {
        display: none !important;
    }

    /* Lappuses iestatījumi */
    @page {
        size: A4 landscape;
        margin: 15mm 12mm 18mm 12mm;
    }

    /* Novērš tukšu otro lappusi */
    html, body {
        height: auto !important;
        overflow: hidden !important;
    }

    body {
        background: #fff !important;
        color: #111 !important;
        font-size: 10pt;
    }

    /* Virsraksts */
    .page-noma .noma-header h2 {
        font-size: 18pt;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 2mm;
        border-bottom: 2px solid #1a1a2e;
        padding-bottom: 3mm;
    }

    /* Drukāšanas datums zem virsraksta */
    .page-noma .noma-header::after {
        content: "Izdrukāts: " attr(data-print-date);
        display: block;
        font-size: 8pt;
        color: #555;
        margin-top: 1mm;
    }

    /* Tabula */
    .page-noma .noma-table-wrap {
        overflow: visible !important;
    }

    .page-noma .noma-table {
        width: 100% !important;
        border-collapse: collapse !important;
        border: 1px solid #aaa !important;
        border-radius: 0 !important;
        font-size: 8.5pt;
        page-break-inside: auto;
    }

    .page-noma .noma-table thead tr {
        background: none !important;
        color: #111 !important;
    }

    .page-noma .noma-table thead th {
        padding: 4pt 5pt !important;
        font-size: 8pt;
        font-weight: 700;
        border: 1px solid #aaa !important;
        text-align: center;
        background: none !important;
    }

    /* Kārtošanas saites */
    .page-noma .noma-table thead th a {
        color: #111 !important;
        text-decoration: none !important;
    }

    .page-noma .noma-table thead th .sort-icon {
        display: none !important;
    }

    /* Tabulas rindas - bez krāsām */
    .page-noma .noma-table tbody tr {
        page-break-inside: avoid;
        background: none !important;
    }

    .page-noma .noma-table tbody tr:nth-child(even) {
        background: none !important;
    }

    .page-noma .noma-table tbody td {
        padding: 3.5pt 5pt !important;
        border: 1px solid #aaa !important;
        vertical-align: middle;
        color: #111 !important;
        background: none !important;
    }

    /* Slēpj darbību kolonnu */
    .page-noma .noma-table thead th:last-child,
    .page-noma .noma-table tbody td:last-child {
        display: none !important;
    }
}
</style>

</div>

@endsection