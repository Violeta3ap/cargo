@extends('layout.app')

@section('content')
<div class="page-noma-arhivs">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Nomas arhīvs</h2>
        <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
            <a href="/Noma">Atpakaļ uz nomām</a>
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

    <table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
        <thead>
            <tr>
                <th>Nomas Nr.</th>
                <th>Klients</th>
                <th>Klienta uzņēmums</th>
                <th>Kravas veids</th>
                <th>Vagona nosaukums</th>
                <th>Vagonu skaits</th>
                <th>Nomas sākuma periods</th>
                <th>Nomas beigu periods</th>
                <th>Kopējā maksa</th>
                <th>Nomas statuss</th>
                <th>Maksas statuss</th>
                <th>Nomas pabeigšanas statuss</th>
                <th>Darbības</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($arhivs as $item)
                <tr>
                    <td>{{ $item->NomasID }}</td>
                    <td>{{ $item->KlientaVards ?? ('ID: '.$item->KlientaID) }} {{ $item->KlientaUzvards ?? '' }}</td>
                    <td>{{ $item->KlientaUznemums ?? ('ID: '.$item->KlientaID) }}</td>
                    <td>{{ $item->KravasNosaukums ?? ('ID: '.$item->KravasID) }}</td>
                    <td>{{ $item->VeidaNosaukums ?? ('ID: '.$item->VeidaID) }}</td>
                    <td>{{ $item->VagonuSkaits }}</td>
                    <td>{{ $item->NomasSakumaPeriods }}</td>
                    <td>{{ $item->NomasBeiguPeriods }}</td>
                    <td>{{ number_format((float) $item->KopejaMaksa, 2) }} €</td>
                    <td>{{ $item->NomasStatusaNosaukums ?? 'Pieteikts' }}</td>
                    <td>{{ $item->MaksasStatusaNosaukums ?? '-' }}</td>
                    <td>{{ $item->PabeigsanasStatuss ?? '-' }}</td>
                    <td>
                        <a href="/Noma/arhivs/{{ $item->NomasID }}/restore"
                           onclick="return confirm('Vai tiešām vēlies atjaunot šo nomu no arhīva?');"
                           class="btn-action">Atjaunot</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">Arhīvā nav ierakstu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($arhivs->hasPages())
        <div style="margin-top: 15px; display: flex; justify-content: center;">
            <nav class="noma-pagination" aria-label="Arhīva lapu navigācija">
                <a href="{{ $arhivs->onFirstPage() ? '#' : $arhivs->previousPageUrl() }}"
                   class="page-btn {{ $arhivs->onFirstPage() ? 'disabled' : '' }}"
                   {{ $arhivs->onFirstPage() ? 'aria-disabled=true tabindex=-1' : '' }}>
                    &lsaquo; Iepriekšējā
                </a>

                @foreach ($arhivs->getUrlRange(1, $arhivs->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn number {{ $page == $arhivs->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                <a href="{{ $arhivs->hasMorePages() ? $arhivs->nextPageUrl() : '#' }}"
                   class="page-btn {{ $arhivs->hasMorePages() ? '' : 'disabled' }}"
                   {{ $arhivs->hasMorePages() ? '' : 'aria-disabled=true tabindex=-1' }}>
                    Nākamā &rsaquo;
                </a>
            </nav>
        </div>
    @endif
</div>
@endsection
