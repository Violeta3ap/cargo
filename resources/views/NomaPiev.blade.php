@extends('layout.app')

@section('content')

<h2>Pievienot jaunus nomas datus</h2>
<a href="/Noma" style="border-radius:8px; border: 1px solid #59c1cf; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
    Atpakaļ
</a>

<hr>

@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 15px; color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 6px; padding: 10px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="/Noma/jaunsSubmit" id="nomaForm">
    @csrf

    <!-- Klienta izvēlne ar pilnu informāciju -->
    <div class="form-group">
        <label for="KlientaID">Klients (vārds, uzvārds, uzņēmums):</label>
        <select class="form-control" id="KlientaID" name="KlientaID" required>
            <option value="">Izvēlieties klientu</option>
            @foreach($klienti as $klientis)
                <option value="{{ $klientis->KlientaID }}" {{ old('KlientaID') == $klientis->KlientaID ? 'selected' : '' }}>
                    {{ $klientis->Vards }} {{ $klientis->Uzvards }} ({{ $klientis->UznemumaNosaukums }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Kravas izvēlne -->
    <div class="form-group">
        <label for="KravasID">Kravas veida nosaukums:</label>
        <select class="form-control" id="KravasID" name="KravasID" required>
            <option value="">Izvēlieties kravas veidu</option>
            @foreach($kravas as $krava)
                <option value="{{ $krava->KravasID }}" data-veida-id="{{ $krava->VeidaID }}" {{ old('KravasID') == $krava->KravasID ? 'selected' : '' }}>
                    {{ $krava->Nosaukums }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Vagona veids (var mainīt) -->
    <div class="form-group">
        <label for="VeidaID">Vagona veida nosaukums:</label>
        <select class="form-control" id="VeidaID" name="VeidaID" required>
            <option value="">Izvēlieties vagona veidu</option>
            @foreach($veidi as $veids)
                <option value="{{ $veids->VeidaID }}" data-cena="{{ $veids->CenaParDiennakti }}">
                    {{ $veids->Nosaukums }}
                </option>
            @endforeach
        </select>
        <small style="font-size: 12px; color: #6c757d;">Pēc kravas izvēles vagona veids tiks ielādēts automātiski, bet to var mainīt</small>
    </div>

    <!-- Vagonu skaits -->
    <div class="form-group">
        <label for="VagonuSkaits">Vagonu skaits:</label>
        <input type="number" class="form-control" id="VagonuSkaits" name="VagonuSkaits" min="1" value="{{ old('VagonuSkaits', 1) }}" required>
    </div>

    <!-- Nomas sākuma datums -->
    <div class="form-group">
        <label for="NomasSakumaPeriods">Nomas sākuma periods:</label>
        <input type="text" class="form-control datepicker" id="NomasSakumaPeriods" name="NomasSakumaPeriods" value="{{ old('NomasSakumaPeriods') }}" placeholder="YYYY-MM-DD" autocomplete="off" required>
    </div>

    <!-- Nomas beigu datums -->
    <div class="form-group">
        <label for="NomasBeiguPeriods">Nomas beigu periods:</label>
        <input type="text" class="form-control datepicker" id="NomasBeiguPeriods" name="NomasBeiguPeriods" value="{{ old('NomasBeiguPeriods') }}" placeholder="YYYY-MM-DD" autocomplete="off" required>
    </div>

    <!-- Cena par diennakti (tikai lasīšanai, ņem no Veidi tabulas) -->
    <div class="form-group">
        <label for="CenaParDiennakti">Cena par diennakti (€):</label>
        <input type="text" class="form-control" id="CenaParDiennakti" readonly style="background-color: #f5f5f5;" placeholder="Tiks aprēķināta automātiski">
    </div>

    <!-- Dienu skaits (automātiski) -->
    <div class="form-group">
        <label for="DienuSkaits">Dienu skaits:</label>
        <input type="text" class="form-control" id="DienuSkaits" readonly style="background-color: #f5f5f5;">
    </div>

    <!-- Kopējā maksa (automātiski aprēķināta) -->
    <div class="form-group">
        <label for="KopejaMaksa">Kopējā maksa (€):</label>
        <input type="number" class="form-control" id="KopejaMaksa" name="KopejaMaksa" step="0.01" readonly style="background-color: #f5f5f5;" required>
        <small style="font-size: 12px; color: #6c757d;">Kopējā maksa tiek aprēķināta automātiski</small>
    </div>

    <button type="submit" style="border-radius:8px; border: 1px solid #59c1cf; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Saglabāt
    </button>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializē datuma izvēli
    flatpickr('.datepicker', {
        locale: 'lv',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: true
    });
    
    // Kad tiek izvēlēta krava, ielādē atbilstošo vagona veidu (bet lietotājs var mainīt)
    $('#KravasID').change(function() {
        var selectedOption = $(this).find('option:selected');
        var veidaId = selectedOption.data('veida-id');
        
        if (veidaId) {
            // Atrod un izvēlas atbilstošo vagona veidu dropdownā
            $('#VeidaID').val(veidaId);
            
            // Iegūst cenu no izvēlētā vagona veida un atjauno
            updatePriceFromVehicle();
            
            // Aprēķina kopējo maksu
            calculateTotal();
        } else {
            $('#VeidaID').val('');
            $('#CenaParDiennakti').val('');
            $('#DienuSkaits').val('');
            $('#KopejaMaksa').val('');
        }
    });
    
    // Funkcija, kas atjauno cenu no izvēlētā vagona veida
    function updatePriceFromVehicle() {
        var selectedVeids = $('#VeidaID option:selected');
        var cena = selectedVeids.data('cena');
        
        if (cena) {
            $('#CenaParDiennakti').val(cena);
        } else {
            $('#CenaParDiennakti').val('');
        }
    }
    
    // Kad lietotājs manuāli maina vagona veidu, atjauno cenu un pārrēķina
    $('#VeidaID').change(function() {
        updatePriceFromVehicle();
        calculateTotal();
    });
    
    // Funkcija kopējās maksas aprēķināšanai
    function calculateTotal() {
        var veidaId = $('#VeidaID').val();
        var vagonuSkaits = $('#VagonuSkaits').val();
        var sakumaDatums = $('#NomasSakumaPeriods').val();
        var beiguDatums = $('#NomasBeiguPeriods').val();
        var cenaParDiennakti = $('#CenaParDiennakti').val();
        
        if (veidaId && vagonuSkaits && sakumaDatums && beiguDatums && cenaParDiennakti) {
            // Aprēķina dienu skaitu
            var start = new Date(sakumaDatums);
            var end = new Date(beiguDatums);
            var dienuSkaits = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
            
            if (dienuSkaits > 0 && !isNaN(dienuSkaits)) {
                $('#DienuSkaits').val(dienuSkaits);
                
                // Aprēķina kopējo maksu
                var kopejaMaksa = parseFloat(cenaParDiennakti) * parseInt(vagonuSkaits) * dienuSkaits;
                $('#KopejaMaksa').val(kopejaMaksa.toFixed(2));
            } else {
                $('#DienuSkaits').val('');
                $('#KopejaMaksa').val('');
            }
        } else {
            $('#DienuSkaits').val('');
            $('#KopejaMaksa').val('');
        }
    }
    
    // Aprēķini pie datumu izmaiņām
    $('#NomasSakumaPeriods, #NomasBeiguPeriods').change(function() {
        calculateTotal();
    });
    
    // Aprēķini pie vagonu skaita izmaiņām
    $('#VagonuSkaits').on('input', function() {
        calculateTotal();
    });
    
    // Ielādē sākotnējo cenu, ja jau ir izvēlēts vagona veids
    if ($('#VeidaID').val()) {
        updatePriceFromVehicle();
    }
    
    // Ielādē sākotnējo kravas veidu, ja tāds ir izvēlēts (no old())
    if ($('#KravasID').val()) {
        $('#KravasID').trigger('change');
    }
});
</script>

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #59c1cf;
        box-shadow: 0 0 5px rgba(89, 193, 207, 0.3);
    }

    button[type="submit"] {
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: transform 0.2s;
    }

    button[type="submit"]:hover {
        transform: scale(1.05);
    }
</style>

@endsection