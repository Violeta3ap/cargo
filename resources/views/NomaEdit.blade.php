@extends('layout.app')

@section('content')
    <h2>Rediģēt nomu</h2>
    <a href="/Noma" style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 15px; color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 6px; padding: 10px;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="/Noma/{{ $noma->NomasID }}/editSubmit" method="POST" id="nomaForm">
        @csrf

        <div class="form-group">
            <label for="KlientaID">Klients:</label>
            <select class="form-control" id="KlientaID" name="KlientaID" required>
                @foreach($klienti as $klientis)
                    <option value="{{ $klientis->KlientaID }}"
                        {{ $klientis->KlientaID == $noma->KlientaID ? 'selected' : '' }}>
                        {{ $klientis->Vards }} {{ $klientis->Uzvards }} ({{ $klientis->UznemumaNosaukums }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="KravasID">Kravas veida nosaukums:</label>
            <select class="form-control" id="KravasID" name="KravasID" required>
                @foreach($kravas as $krava)
                    <option value="{{ $krava->KravasID }}"
                        {{ $krava->KravasID == $noma->KravasID ? 'selected' : '' }}>
                        {{ $krava->Nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="VeidaID">Vagona veida nosaukums:</label>
            <select class="form-control" id="VeidaID" name="VeidaID" required>
                @foreach($veidi as $veids)
                    <option value="{{ $veids->VeidaID }}"
                        {{ $veids->VeidaID == $noma->VeidaID ? 'selected' : '' }}>
                        {{ $veids->Nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="VagonuSkaits">Vagonu skaits:</label>
            <input type="number" class="form-control" id="VagonuSkaits" name="VagonuSkaits" value="{{ $noma->VagonuSkaits }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="NomasSakumaPeriods">Nomas sākuma periods:</label>
            <input type="text" class="form-control datepicker" id="NomasSakumaPeriods" name="NomasSakumaPeriods" value="{{ $noma->NomasSakumaPeriods }}" placeholder="YYYY-MM-DD" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label for="NomasBeiguPeriods">Nomas beigu periods:</label>
            <input type="text" class="form-control datepicker" id="NomasBeiguPeriods" name="NomasBeiguPeriods" value="{{ $noma->NomasBeiguPeriods }}" placeholder="YYYY-MM-DD" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label for="CenaParDiennakti">Cena par diennakti (€):</label>
            <input type="text" class="form-control" id="CenaParDiennakti" readonly style="background-color: #f5f5f5;">
        </div>

        <div class="form-group">
            <label for="DienuSkaits">Dienu skaits:</label>
            <input type="text" class="form-control" id="DienuSkaits" readonly style="background-color: #f5f5f5;">
        </div>

        <div class="form-group">
            <label for="KopejaMaksa">Kopējā maksa (€):</label>
            <input type="number" class="form-control" id="KopejaMaksa" name="KopejaMaksa" step="0.01" readonly style="background-color: #f5f5f5;" required>
            <small style="font-size: 12px; color: #6c757d;">Kopējā maksa tiek aprēķināta automātiski</small>
        </div>

        <button type="submit" style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
            Atjaunināt
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
    
    // Funkcija kopējās maksas aprēķināšanai
    function calculateTotal() {
        var veidaId = $('#VeidaID').val();
        var vagonuSkaits = $('#VagonuSkaits').val();
        var sakumaDatums = $('#NomasSakumaPeriods').val();
        var beiguDatums = $('#NomasBeiguPeriods').val();
        
        if (veidaId && vagonuSkaits && sakumaDatums && beiguDatums) {
            $.ajax({
                url: '/api/noma/calculate',
                type: 'POST',
                data: {
                    veida_id: veidaId,
                    vagonu_skaits: vagonuSkaits,
                    sakuma_datums: sakumaDatums,
                    beigu_datums: beiguDatums,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#CenaParDiennakti').val(data.cena_par_diennakti);
                        $('#DienuSkaits').val(data.dienu_skaits);
                        $('#KopejaMaksa').val(data.kopeja_maksa);
                    } else {
                        $('#KopejaMaksa').val('');
                    }
                }
            });
        }
    }
    
    // Kad tiek izvēlēta krava, ielādē atbilstošo vagona veidu
    $('#KravasID').change(function() {
        var kravasId = $(this).val();
        
        if (kravasId) {
            $.ajax({
                url: '/api/krava/' + kravasId + '/veids',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Atjauno vagona veidu dropdown
                        $('#VeidaID').empty();
                        $('#VeidaID').append('<option value="' + data.veida_id + '">' + data.veida_nosaukums + '</option>');
                        
                        // Parāda cenu par diennakti
                        $('#CenaParDiennakti').val(data.cena_par_diennakti);
                        
                        // Aprēķina kopējo maksu
                        calculateTotal();
                    } else {
                        alert('Kļūda: ' + data.message);
                    }
                },
                error: function() {
                    alert('Kļūda ielādējot vagona veidu');
                }
            });
        }
    });
    
    // Kad mainās vagona veids, pārrēķina
    $('#VeidaID').change(function() {
        calculateTotal();
    });
    
    // Aprēķini pie datumu izmaiņām
    $('#NomasSakumaPeriods, #NomasBeiguPeriods').change(function() {
        calculateTotal();
    });
    
    // Aprēķini pie vagonu skaita izmaiņām
    $('#VagonuSkaits').on('input', function() {
        calculateTotal();
    });
    
    // Sākotnējais aprēķins, kad lapa ir ielādēta
    setTimeout(function() {
        // Iegūst cenu no pašreizējā vagona veida
        var kravasId = $('#KravasID').val();
        if (kravasId) {
            $.ajax({
                url: '/api/krava/' + kravasId + '/veids',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#CenaParDiennakti').val(data.cena_par_diennakti);
                        calculateTotal();
                    }
                }
            });
        }
    }, 100);
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