@extends('layout.app')

@section('content')
    
    <h2>Rediģēt nomu</h2>
    <a href="/Noma" style="border-radius:8px; border: 1px solid #C2CBD1; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">Atpakaļ</a>

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
            <label for="KlientaID">Klients (vārds, uzvārds, uzņēmums):</label>
            @if(Auth::check() && Auth::user()->isKlients())
                @php $authKlients = Auth::user()->klienti; @endphp
                @if($authKlients)
                    <input type="text" class="form-control" readonly style="background-color: #f5f5f5;"
                           value="{{ $authKlients->Vards }} {{ $authKlients->Uzvards }} ({{ $authKlients->UznemumaNosaukums }})">
                    <input type="hidden" id="KlientaID" name="KlientaID" value="{{ $authKlients->KlientaID }}">
                @else
                    <input type="text" class="form-control" readonly style="background-color: #f5f5f5;" value="Nav atrasts piesaistīts klienta ieraksts">
                    <input type="hidden" id="KlientaID" name="KlientaID" value="">
                @endif
            @else
                <select class="form-control" id="KlientaID" name="KlientaID" required>
                    @foreach($klienti as $klientis)
                        <option value="{{ $klientis->KlientaID }}"
                            {{ $klientis->KlientaID == $noma->KlientaID ? 'selected' : '' }}>
                            {{ $klientis->Vards }} {{ $klientis->Uzvards }} ({{ $klientis->UznemumaNosaukums }})
                        </option>
                    @endforeach
                </select>
            @endif
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
            <label for="KravasID">Kravas veida nosaukums:</label>
            <select class="form-control" id="KravasID" name="KravasID" required>
                @foreach($kravas as $krava)
                    <option value="{{ $krava->KravasID }}"
                        data-veida-id="{{ $krava->VeidaID }}"
                        data-veida-nosaukums="{{ $krava->veidi->Nosaukums ?? '' }}"
                        data-cena="{{ $krava->veidi->CenaParDiennakti ?? 0 }}"
                        data-kopejais-vagonu-skaits="{{ $krava->veidi->VagonuSkaits ?? 0 }}"
                        {{ $krava->KravasID == $noma->KravasID ? 'selected' : '' }}>
                        {{ $krava->Nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        
        <div class="form-group">
            <label for="VeidaID_Nosaukums">Vagona veida nosaukums:</label>
            <input type="text" class="form-control" id="VeidaID_Nosaukums" readonly style="background-color: #f5f5f5;" value="{{ $noma->veidi->Nosaukums ?? '' }}">
            <input type="hidden" id="VeidaID" name="VeidaID" value="{{ $noma->VeidaID }}">
            <div id="vehicleInfo" style="font-size: 12px; color: #6c757d; margin-top: 5px;"></div>
        </div>

        
        <div class="form-group">
            <label for="VagonuSkaits">Vagonu skaits:</label>
            <input type="number" class="form-control" id="VagonuSkaits" name="VagonuSkaits" min="1" value="{{ $noma->VagonuSkaits }}" required>
            <div id="availabilityLimitHint" style="font-size: 12px; color: #6c757d; margin-top: 5px;"></div>
            <div id="availabilityMessage" style="font-size: 12px; margin-top: 5px;"></div>
        </div>

        
        <div class="form-group">
            <label for="CenaParDiennakti">Cena par diennakti (€):</label>
            <input type="text" class="form-control" id="CenaParDiennakti" readonly style="background-color: #f5f5f5;" value="{{ $noma->veidi->CenaParDiennakti ?? 0 }}">
        </div>

        
        <div class="form-group">
            <label for="DienuSkaits">Dienu skaits:</label>
            <input type="text" class="form-control" id="DienuSkaits" readonly style="background-color: #f5f5f5;">
        </div>

        
        <div class="form-group">
            <label for="KopejaMaksa_Display">Kopējā maksa (€):</label>
            <input type="text" class="form-control" id="KopejaMaksa_Display" readonly style="background-color: #f5f5f5;" value="{{ number_format($noma->KopejaMaksa, 2) }}">
            <input type="hidden" id="KopejaMaksa" name="KopejaMaksa" value="{{ $noma->KopejaMaksa }}">
            <small style="font-size: 12px; color: #6c757d;">Kopējā maksa tiek aprēķināta automātiski</small>
        </div>

        @if(Auth::check() && Auth::user()->isAdmin() && isset($nomasStatusi) && $nomasStatusi->count())
            <div class="form-group">
                <label for="StatusaID">Nomas statuss:</label>
                <select class="form-control" id="StatusaID" name="StatusaID">
                    <option value="">Izvēlieties statusu</option>
                    @foreach($nomasStatusi as $statuss)
                        <option value="{{ $statuss->StatusaID }}" data-status-name="{{ mb_strtolower($statuss->Nosaukums) }}" {{ (int) $noma->StatusaID === (int) $statuss->StatusaID ? 'selected' : '' }}>
                            {{ $statuss->Nosaukums }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="atteikumaIemeslsWrap" style="display: none; border: 1px solid #f5c2c7; border-radius: 8px; padding: 12px; background-color: #fff4f4;">
                <label for="AtteikumaIemesls" style="font-weight: 600;">Atteikuma iemesls</label>
                <textarea class="form-control" id="AtteikumaIemesls" name="AtteikumaIemesls" rows="4" maxlength="2000" placeholder="Aprakstiet, kāpēc nomas pieteikums tiek noraidīts...">{{ old('AtteikumaIemesls', $noma->AtteikumaIemesls ?? '') }}</textarea>
                <small style="font-size: 12px; color: #6c757d;">Šis lauks ir obligāts, ja nomas statuss ir "Noraidīts".</small>
            </div>
        @endif

        @if(Auth::check() && Auth::user()->isAdmin() && isset($maksasStatusi) && $maksasStatusi->count())
            <div class="form-group">
                <label for="MaksasID">Maksas statuss:</label>
                <select class="form-control" id="MaksasID" name="MaksasID">
                    <option value="">Izvēlieties statusu</option>
                    @foreach($maksasStatusi as $statuss)
                        <option value="{{ $statuss->MaksasID }}" data-maksas-name="{{ mb_strtolower($statuss->Nosaukums) }}" {{ (int) $noma->MaksasID === (int) $statuss->MaksasID ? 'selected' : '' }}>
                            {{ $statuss->Nosaukums }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <button type="submit" id="submitBtn" style="border-radius:8px; border: 1px solid #C2CBD1; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
            Atjaunināt
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
    var statusSelect = $('#StatusaID');
    var maksasSelect = $('#MaksasID');
    var atteikumaWrap = $('#atteikumaIemeslsWrap');
    var atteikumaInput = $('#AtteikumaIemesls');

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

    function isNoraiditsSelected() {
        if (!statusSelect.length) {
            return false;
        }

        var selected = statusSelect.find('option:selected');
        var statusName = (selected.data('status-name') || '').toString().toLowerCase();
        return statusName.indexOf('noraid') !== -1;
    }

    function isPieteiktsSelected() {
        if (!statusSelect.length) {
            return false;
        }

        var selected = statusSelect.find('option:selected');
        var statusName = (selected.data('status-name') || '').toString().toLowerCase();
        return statusName.indexOf('pieteikt') !== -1;
    }

    function isNavApmaksatsSelected() {
        if (!maksasSelect.length) {
            return false;
        }

        var selected = maksasSelect.find('option:selected');
        var maksasName = (selected.data('maksas-name') || '').toString().toLowerCase();
        return maksasName.indexOf('nav apmaks') !== -1 || maksasName.indexOf('neapmaks') !== -1;
    }

    function enforceStatusMaksasRule() {
        if (!statusSelect.length || !maksasSelect.length) {
            return;
        }

        var pieteiktsOption = statusSelect.find('option').filter(function () {
            var name = ($(this).data('status-name') || '').toString().toLowerCase();
            return name.indexOf('pieteikt') !== -1;
        });

        var lockPieteikts = isNavApmaksatsSelected();
        pieteiktsOption.prop('disabled', lockPieteikts);

        if (lockPieteikts && isPieteiktsSelected()) {
            statusSelect.val('');
        }
    }

    function toggleAtteikumaIemesls() {
        if (!atteikumaWrap.length || !atteikumaInput.length) {
            return;
        }

        if (isNoraiditsSelected()) {
            atteikumaWrap.show();
            atteikumaInput.prop('required', true);
        } else {
            atteikumaWrap.hide();
            atteikumaInput.prop('required', false);
            atteikumaInput.val('');
        }
    }
    
    // Funkcija pieejamības pārbaudei
    function checkAvailability() {
        var veidaId = $('#VeidaID').val();
        var vagonuSkaits = parseInt($('#VagonuSkaits').val()) || 0;
        var sakumaDatums = $('#NomasSakumaPeriods').val();
        var beiguDatums = $('#NomasBeiguPeriods').val();
        var nomasId = {{ $noma->NomasID }};

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
                    nomas_id: nomasId,
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
                error: function() {
                    messageDiv.html('');
                    limitHintDiv.text('');
                    submitBtn.prop('disabled', false);
                    submitBtn.css('opacity', '1');
                    vagonuSkaitsInput.removeAttr('max');
                }
            });
        } else {
            messageDiv.html('');
            limitHintDiv.text('');
            submitBtn.prop('disabled', false);
            submitBtn.css('opacity', '1');
            vagonuSkaitsInput.removeAttr('max');
        }
    }
    
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
                $('#KopejaMaksa_Display').val(kopejaMaksa.toFixed(2));
                $('#KopejaMaksa').val(kopejaMaksa.toFixed(2));
            } else {
                $('#DienuSkaits').val('');
                $('#KopejaMaksa_Display').val('');
                $('#KopejaMaksa').val('');
            }
        } else {
            $('#DienuSkaits').val('');
            $('#KopejaMaksa_Display').val('');
            $('#KopejaMaksa').val('');
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
            $('#KopejaMaksa_Display').val('');
            $('#KopejaMaksa').val('');
            $('#VagonuSkaits').removeAttr('max');
        }
    });
    
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

    if (statusSelect.length) {
        statusSelect.on('change', function() {
            toggleAtteikumaIemesls();
        });
    }

    if (maksasSelect.length) {
        maksasSelect.on('change', function() {
            enforceStatusMaksasRule();
        });
    }

    $('#nomaForm').on('submit', function(e) {
        if (isNoraiditsSelected()) {
            var value = (atteikumaInput.val() || '').trim();
            if (!value) {
                e.preventDefault();
                alert('Lūdzu aizpildiet lauku "Atteikuma iemesls", jo statuss ir Noraidīts.');
                atteikumaInput.focus();
            }
        }
    });
    
    // Sākotnējais aprēķins
    setTimeout(function() {
        calculateTotal();
        if ($('#KravasID').val()) {
            $('#KravasID').trigger('change');
        } else {
            checkAvailability();
        }

        enforceStatusMaksasRule();
        toggleAtteikumaIemesls();
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
    
    button[type="submit"]:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>

@endsection