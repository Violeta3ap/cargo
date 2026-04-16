@extends('layout.app')

@section('content')

<!-- Šī forma lietotājam ļauj pievienot jaunu nomas pieteikumu, izvēloties klientu, datumus un kravas veidu. -->
<h2>Pievienot jaunu nomas pieteikumu</h2>
<a href="/Noma" style="border-radius:8px; border: 1px solid #C2CBD1; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
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

    <!-- 1. Klienta izvēlne -->
    <div class="form-group">
        <label for="KlientaID">Klients (vārds, uzvārds, uzņēmums):</label>
        @if(Auth::check() && Auth::user()->isKlients())
            @php $authKlients = Auth::user()->klienti; @endphp
            @if($authKlients)
                <input type="text" class="form-control" readonly style="background-color: #f5f5f5;"
                       value="{{ $authKlients->Vards }} {{ $authKlients->Uzvards }} ({{ $authKlients->UznemumaNosaukums }})">
                <input type="hidden" id="KlientaID" name="KlientaID" value="{{ old('KlientaID', $authKlients->KlientaID) }}">
            @else
                <input type="text" class="form-control" readonly style="background-color: #f5f5f5;" value="Nav atrasts piesaistīts klienta ieraksts">
                <input type="hidden" id="KlientaID" name="KlientaID" value="">
            @endif
        @else
            <select class="form-control" id="KlientaID" name="KlientaID" required>
                <option value="">Izvēlieties klientu</option>
                @foreach($klienti as $klientis)
                    <option value="{{ $klientis->KlientaID }}" {{ old('KlientaID') == $klientis->KlientaID ? 'selected' : '' }}>
                        {{ $klientis->Vards }} {{ $klientis->Uzvards }} ({{ $klientis->UznemumaNosaukums }})
                    </option>
                @endforeach
            </select>
        @endif
    </div>

    <!-- 2. Laika periods (nomas sākuma un beigu datumi) -->
    <div class="form-group">
        <label for="NomasSakumaPeriods">Nomas sākuma periods:</label>
        <input type="text" class="form-control datepicker" id="NomasSakumaPeriods" name="NomasSakumaPeriods" value="{{ old('NomasSakumaPeriods') }}" placeholder="YYYY-MM-DD" autocomplete="off" required>
    </div>

    <div class="form-group">
        <label for="NomasBeiguPeriods">Nomas beigu periods:</label>
        <input type="text" class="form-control datepicker" id="NomasBeiguPeriods" name="NomasBeiguPeriods" value="{{ old('NomasBeiguPeriods') }}" placeholder="YYYY-MM-DD" autocomplete="off" required>
    </div>

    <!-- 3. Kravas izvēlne -->
    <div class="form-group">
        <label for="KravasID">Kravas veida nosaukums:</label>
        <select class="form-control" id="KravasID" name="KravasID" required>
            <option value="">Izvēlieties kravas veidu</option>
            @foreach($kravas as $krava)
                <option value="{{ $krava->KravasID }}" data-veida-id="{{ $krava->VeidaID }}" data-veida-nosaukums="{{ $krava->veidi->Nosaukums ?? '' }}" data-cena="{{ $krava->veidi->CenaParDiennakti ?? 0 }}" data-kopejais-vagonu-skaits="{{ $krava->veidi->VagonuSkaits ?? 0 }}">
                    {{ $krava->Nosaukums }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- 4. Vagona veids (tikai lasīšanai) -->
    <div class="form-group">
        <label for="VeidaID_Nosaukums">Vagona veida nosaukums:</label>
        <input type="text" class="form-control" id="VeidaID_Nosaukums" readonly style="background-color: #f5f5f5;" placeholder="Tiks ielādēts automātiski pēc kravas izvēles">
        <input type="hidden" id="VeidaID" name="VeidaID">
        <div id="vehicleInfo" style="font-size: 12px; color: #6c757d; margin-top: 5px;"></div>
    </div>

    <!-- 5. Vagonu skaits (ar pieejamības pārbaudi) -->
    <div class="form-group">
        <label for="VagonuSkaits">Vagonu skaits:</label>
        <input type="number" class="form-control" id="VagonuSkaits" name="VagonuSkaits" min="1" value="{{ old('VagonuSkaits', 1) }}" required>
        <div id="availabilityLimitHint" style="font-size: 12px; color: #6c757d; margin-top: 5px;"></div>
        <div id="availabilityMessage" style="font-size: 12px; margin-top: 5px;"></div>
    </div>

    <!-- Cena par diennakti -->
    <div class="form-group">
        <label for="CenaParDiennakti">Cena par diennakti (€):</label>
        <input type="text" class="form-control" id="CenaParDiennakti" readonly style="background-color: #f5f5f5;" placeholder="Tiks aprēķināta automātiski">
    </div>

    <!-- Dienu skaits -->
    <div class="form-group">
        <label for="DienuSkaits">Dienu skaits:</label>
        <input type="text" class="form-control" id="DienuSkaits" readonly style="background-color: #f5f5f5;">
    </div>

    <!-- Kopējā maksa -->
    <div class="form-group">
        <label for="KopejaMaksa">Kopējā maksa (€):</label>
        <input type="number" class="form-control" id="KopejaMaksa" name="KopejaMaksa" step="0.01" readonly style="background-color: #f5f5f5;" required>
        <small style="font-size: 12px; color: #6c757d;">Kopējā maksa tiek aprēķināta automātiski</small>
    </div>

    <button type="submit" id="submitBtn" style="border-radius:8px; border: 1px solid #C2CBD1; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Saglabāt
    </button>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    var messageDiv = $('#availabilityMessage');
    var limitHintDiv = $('#availabilityLimitHint');
    var submitBtn = $('#submitBtn');
    var vagonuSkaitsInput = $('#VagonuSkaits');

    // Inicializē datuma izvēli
    flatpickr('.datepicker', {
        locale: 'lv',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: true
    });

    function setAvailabilityState(message, color, isDisabled, maxCount) {
        messageDiv.html('<span style="color: ' + color + ';">' + message + '</span>');
        submitBtn.prop('disabled', isDisabled);
        submitBtn.css('opacity', isDisabled ? '0.5' : '1');

        if (maxCount && maxCount > 0) {
            vagonuSkaitsInput.attr('max', maxCount);
            limitHintDiv.text('Maksimāli vari izvēlēties ' + maxCount + ' vagonus šajā periodā.');
        } else {
            vagonuSkaitsInput.removeAttr('max');
            limitHintDiv.text('');
        }
    }
    
    // Funkcija pieejamības pārbaudei
    function checkAvailability() {
        var veidaId = $('#VeidaID').val();
        var vagonuSkaits = parseInt($('#VagonuSkaits').val()) || 0;
        var sakumaDatums = $('#NomasSakumaPeriods').val();
        var beiguDatums = $('#NomasBeiguPeriods').val();

        if (sakumaDatums && beiguDatums && new Date(beiguDatums) < new Date(sakumaDatums)) {
            setAvailabilityState('Nomas beigu datumam jābūt vienādam ar sākuma datumu vai vēlākam.', 'red', true);
            return;
        }
        
        if (veidaId && vagonuSkaits > 0 && sakumaDatums && beiguDatums) {
            $.ajax({
                url: '/api/noma/check-availability',
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
                        if (data.ir_pieejams) {
                            setAvailabilityState('✓ Šajā periodā pieejami ' + data.pieejamais_skaits + ' vagoni. Jūs pieprasāt ' + data.pieprasitais_skaits + ' vagonus.', 'green', false, data.pieejamais_skaits);
                        } else {
                            setAvailabilityState('✗ Šajā periodā pieejami tikai ' + data.pieejamais_skaits + ' vagoni, bet jūs pieprasāt ' + data.pieprasitais_skaits + '.', 'red', true, data.pieejamais_skaits);
                        }
                    }
                },
            });
        } else {
            messageDiv.html('');
            limitHintDiv.text('');
            submitBtn.prop('disabled', false);
            submitBtn.css('opacity', '1');
            vagonuSkaitsInput.removeAttr('max');
        }
    }
    
    // Kad tiek izvēlēta krava, ielādē atbilstošo vagona veidu
    $('#KravasID').change(function() {
        var selectedOption = $(this).find('option:selected');
        var veidaId = selectedOption.data('veida-id');
        var veidaNosaukums = selectedOption.data('veida-nosaukums');
        var cena = selectedOption.data('cena');
        var kopejaisVagonuSkaits = selectedOption.data('kopejais-vagonu-skaits');
        
        if (veidaId && veidaNosaukums) {
            $('#VeidaID_Nosaukums').val(veidaNosaukums);
            $('#VeidaID').val(veidaId);
            $('#CenaParDiennakti').val(cena);
            $('#vehicleInfo').html('Kopējais vagonu skaits šim tipam: ' + kopejaisVagonuSkaits);
            
            // Pārbauda pieejamību
            checkAvailability();
            calculateTotal();
        } else {
            $('#VeidaID_Nosaukums').val('');
            $('#VeidaID').val('');
            $('#CenaParDiennakti').val('');
            $('#vehicleInfo').html('');
            $('#availabilityMessage').html('');
            $('#availabilityLimitHint').html('');
            $('#DienuSkaits').val('');
            $('#KopejaMaksa').val('');
            $('#VagonuSkaits').removeAttr('max');
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
            var start = new Date(sakumaDatums);
            var end = new Date(beiguDatums);
            var dienuSkaits = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
            
            if (dienuSkaits > 0 && !isNaN(dienuSkaits)) {
                $('#DienuSkaits').val(dienuSkaits);
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
    
    // Event listeners
    $('#NomasSakumaPeriods, #NomasBeiguPeriods').change(function() {
        checkAvailability();
        calculateTotal();
    });
    
    $('#VagonuSkaits').on('input', function() {
        var maxSkaits = parseInt($(this).attr('max'), 10);
        var currentValue = parseInt($(this).val(), 10);

        if (!isNaN(maxSkaits) && maxSkaits > 0 && currentValue > maxSkaits) {
            $(this).val(maxSkaits);
        }

        checkAvailability();
        calculateTotal();
    });
    
    // Ielādē sākotnējo kravas veidu, ja tāds ir izvēlēts
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
    
    button[type="submit"]:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>

@endsection
