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

    <!-- Klienta vārds -->
    <div class="form-group">
        <label for="KlientaID_Vards">Klienta vārds:</label>
        <select class="form-control" id="KlientaID_Vards" name="KlientaID" required>
            <option value="">Izvēlieties klienta vārdu</option>
            @foreach($klienti as $klientis)
                <option value="{{ $klientis->KlientaID }}" data-uzvards="{{ $klientis->Uzvards }}" data-uznemums="{{ $klientis->UznemumaNosaukums }}" {{ old('KlientaID') == $klientis->KlientaID ? 'selected' : '' }}>
                    {{ $klientis->Vards }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Klienta uzvards -->
    <div class="form-group">
        <label for="KlientaID_Uzvards">Klienta uzvārds:</label>
        <select class="form-control" id="KlientaID_Uzvards" name="KlientaID" required>
            <option value="">Izvēlieties klienta uzvārdu</option>
            @foreach($klienti as $klientis)
                <option value="{{ $klientis->KlientaID }}" data-uzvards="{{ $klientis->Uzvards }}" data-uznemums="{{ $klientis->UznemumaNosaukums }}" {{ old('KlientaID') == $klientis->KlientaID ? 'selected' : '' }}>
                    {{ $klientis->Uzvards }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Klienta uzņēmuma nosaukums -->
    <div class="form-group">
        <label for="KlientaID_Uznemums">Klienta uzņēmuma nosaukums:</label>
        <select class="form-control" id="KlientaID_Uznemums" name="KlientaID" required>
            <option value="">Izvēlieties klienta uzņēmumu</option>
            @foreach($klienti as $klientis)
                <option value="{{ $klientis->KlientaID }}" data-uzvards="{{ $klientis->Uzvards }}" data-uznemums="{{ $klientis->UznemumaNosaukums }}" {{ old('KlientaID') == $klientis->KlientaID ? 'selected' : '' }}>
                    {{ $klientis->UznemumaNosaukums }}
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

    <!-- Vagona veids (tiks automātiski aizpildīts) -->
    <div class="form-group">
        <label for="VeidaID">Vagona veida nosaukums:</label>
        <select class="form-control" id="VeidaID" name="VeidaID" required>
            <option value="">Vispirms izvēlieties kravu</option>
            @foreach($veidi as $veids)
                <option value="{{ $veids->VeidaID }}" data-cena="{{ $veids->CenaParDiennakti }}">
                    {{ $veids->Nosaukums }}
                </option>
            @endforeach
        </select>
        <small style="font-size: 12px; color: #6c757d;">Vagona veids tiks automātiski ielādēts pēc kravas izvēles</small>
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
    
    // Kad tiek izvēlēts klienta vārds, automātiski aizpilda uzvārdu un uzņēmumu
    $('#KlientaID_Vards').change(function() {
        var selectedOption = $(this).find('option:selected');
        var uzvards = selectedOption.data('uzvards');
        var uznemums = selectedOption.data('uznemums');
        
        if ($(this).val()) {
            $('#KlientaUzvards').val(uzvards || '');
            $('#KlientaUznemums').val(uznemums || '');
        } else {
            $('#KlientaUzvards').val('');
            $('#KlientaUznemums').val('');
        }
    });
    
    // Ielādē sākotnējās vērtības, ja ir izvēlēts klients
    if ($('#KlientaID_Vards').val()) {
        $('#KlientaID_Vards').trigger('change');
    }
    
    // Kad tiek izvēlēta krava, ielādē atbilstošo vagona veidu
    $('#KravasID').change(function() {
        var selectedOption = $(this).find('option:selected');
        var veidaId = selectedOption.data('veida-id');
        
        if (veidaId) {
            // Atrod un izvēlas atbilstošo vagona veidu dropdownā
            $('#VeidaID').val(veidaId);
            
            // Iegūst cenu no izvēlētā vagona veida
            var selectedVeids = $('#VeidaID option:selected');
            var cena = selectedVeids.data('cena');
            
            if (cena) {
                $('#CenaParDiennakti').val(cena);
            }
            
            // Aprēķina kopējo maksu
            calculateTotal();
        } else {
            $('#VeidaID').val('');
            $('#CenaParDiennakti').val('');
            $('#DienuSkaits').val('');
            $('#KopejaMaksa').val('');
        }
    });
    
    // Kad mainās vagona veids, atjauno cenu un pārrēķina
    $('#VeidaID').change(function() {
        var selectedOption = $(this).find('option:selected');
        var cena = selectedOption.data('cena');
        
        if (cena) {
            $('#CenaParDiennakti').val(cena);
            calculateTotal();
        }
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
        $('#VeidaID').trigger('change');
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