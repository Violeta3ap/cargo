@extends('layout.app')

@section('content')

<!-- Virsraksts un navigācija -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2>Noma</h2>
    <nav class="navigacija" style="background-color: #ffffff; padding: 5px 10px;">
        <a href="/Noma/jauns">Jauns ieraksts</a>
        <!-- <a href="/VagonuDati">Nomas papildinājums</a> -->
    </nav>
</div>

<!-- Nomas tabula -->
<table class="table table-striped" style="width: 100%; border: 1px solid #59c1cf; border-radius: 8px; overflow: hidden; text-align: center;">
    <thead>
        <tr>
            <th>Nomas ID</th>
            <th>Klients</th>
            <th>Klienta uzņēmums</th>
            <!-- <th>Darbinieks</th> -->
            <th>Krava</th>
            <th>Vagonu skaits</th>
            <th>Nomas sākums</th>
            <th>Nomas beigas</th>
            <!-- <th>Nosūtīšanas stacija</th>
            <th>Galastacija</th> -->
            <th>Kopējā maksa</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($noma as $item)
        <tr>
            <td>{{$item->NomasID}}</td>
            <td>{{$item->klienti->Vards ?? ('ID: '.$item->KlientaID)}} {{$item->klienti->Uzvards ?? ''}}</td>
            <td>{{$item->klienti->UznemumaNosaukums ?? ('ID: '.$item->KlientaID)}}</td>
            <!-- <td>{{$item->darbinieki->Vards ?? ('ID: '.$item->DarbiniekaID)}} {{$item->darbinieki->Uzvards ?? ''}}</td> -->
            <td>{{$item->kravas->Nosaukums ?? ('ID: '.$item->KravasID)}}</td>
            <td>{{$item->VagonuSkaits}}</td>
            <td>{{$item->NomasSakumaPeriods}}</td>
            <td>{{$item->NomasBeiguPeriods}}</td>
            <!-- <td>{{$item->NosutisanasStacija}}</td>
            <td>{{$item->Galastacija}}</td> -->
            <td>{{$item->KopejaMaksa}}</td>
            <td>
                <div style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                    <a href="/Noma/{{ $item->NomasID }}/details" class="btn-action">Detalizēta</a>
                    <a href="/Noma/{{ $item->NomasID }}/edit" class="btn-action">Rediģēt</a>
                    <a href="/Noma/{{ $item->NomasID }}/delete" onclick="return confirm('Vai tiešām vēlies dzēst šo ierakstu?');" class="btn-action">Dzēst</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>





<!-- paginacijas skripts un stili -->

<script>
const PER_PAGE = 10;
let currentPage = 1;
let visibleRows = [];
 
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (e) {
        const b = e.relatedTarget;
        document.getElementById('tehnikaName').textContent = b.getAttribute('data-name');
        document.getElementById('confirmDelete').href = '/tehnika/delete/' + b.getAttribute('data-id');
    });
    document.getElementById('filterTips').addEventListener('input', function() {
        currentPage = 1;
        filterAndPaginate();
    });
    filterAndPaginate();
});
 
function filterAndPaginate() {
    const tips = document.getElementById('filterTips').value.toLowerCase();
    const allRows = Array.from(document.querySelectorAll('#tableBody tr'));
    allRows.forEach(r => r.style.display = 'none');
    visibleRows = allRows.filter(row => {
        if (row.cells.length < 2) return false;
        return !tips || row.cells[1]?.textContent.trim().toLowerCase() === tips;
    });
    document.getElementById('noResults').classList.toggle('d-none', visibleRows.length > 0);
    document.getElementById('paginationBar').style.display = visibleRows.length <= PER_PAGE ? 'none' : '';
    renderPage();
}
 
function renderPage() {
    const totalPages = Math.ceil(visibleRows.length / PER_PAGE);
    if (currentPage > totalPages) currentPage = totalPages || 1;
    const start = (currentPage - 1) * PER_PAGE;
    const end   = start + PER_PAGE;
    visibleRows.forEach((row, i) => row.style.display = (i >= start && i < end) ? '' : 'none');
    const from = visibleRows.length ? start + 1 : 0;
    document.getElementById('pageInfo').textContent = 'Rāda ' + from + '–' + Math.min(end, visibleRows.length) + ' no ' + visibleRows.length;
    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;
    const pn = document.getElementById('pageNumbers');
    pn.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm ' + (p === currentPage ? 'btn-success' : 'btn-outline-secondary');
        btn.textContent = p;
        btn.onclick = (function(pg) { return function() { currentPage = pg; renderPage(); }; })(p);
        pn.appendChild(btn);
    }
}
 
function changePage(dir) {
    currentPage = Math.max(1, Math.min(currentPage + dir, Math.ceil(visibleRows.length / PER_PAGE)));
    renderPage();
}
 
function clearFilters() {
    document.getElementById('filterTips').value = '';
    currentPage = 1;
    filterAndPaginate();
}
</script>








<!-- CSS stili tabulai un pogām -->
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
    }
    
    .table tbody tr:hover {
        background-color: #e8f5f7;
    }
    
    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    /* Pogas stils */
    .btn-action {
        border-radius: 8px;
        border: 1px solid #59c1cf;
        padding: 5px 10px;
        color: #000000;
        text-decoration: none;
        background-color: #59c1cf;
        white-space: nowrap;
        font-size: 0.9rem;
        width: 100%; /* lai aizņemtu visu šūnas platumu */
        text-align: center;
    }

    .btn-action:hover {
        background-color: #a2e0ed;
        color: #000;
    }
</style>

@endsection

@if(session('success'))
    <div class="alert alert-success" style="margin-top: 10px;">
        {{ session('success') }}
    </div>  
@endif