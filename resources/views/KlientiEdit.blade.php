@extends('layout.app')

@section('content')

<h2>Rediģēt klientu</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz klientu sarakstu -->
<a href="/Klienti"  
   style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Horizontāla līnija -->

@if($errors->has('duplicate'))
    <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 8px;">
        {{ $errors->first('duplicate') }}
    </div>
@endif

<!-- Formas sākums klienta datu rediģēšanai -->
<form action="/Klienti/{{ $klientis->KlientaID }}/editSubmit" method="POST">
    @csrf <!-- CSRF aizsardzība -->

    <!-- Forma esoša klienta datu rediģēšanai; dati tiek nosūtīti uz kontrolieri. -->
    <!-- Vārds -->
    <div class="form-group">
        <label for="Vards">Vārds:</label>
        <input type="text" class="form-control" id="Vards" name="Vards" value="{{ old('Vards', $klientis->Vards) }}" maxlength="30" data-letters-only="true" data-capitalize="words" autocomplete="off" title="Drīkst ievadīt tikai burtus, un pirmais burts būs liels." required>
    </div>

    <!-- Uzvārds -->
    <div class="form-group">
        <label for="Uzvards">Uzvārds:</label>
        <input type="text" class="form-control" id="Uzvards" name="Uzvards" value="{{ old('Uzvards', $klientis->Uzvards) }}" maxlength="30" data-letters-only="true" data-capitalize="words" autocomplete="off" title="Drīkst ievadīt tikai burtus, un pirmais burts būs liels." required>
    </div>

    <!-- E-pasts -->
    <div class="form-group">
        <label for="Epasts">E-pasts:</label>
        <input type="email" class="form-control" id="Epasts" name="Epasts" value="{{ old('Epasts', $klientis->Epasts) }}" inputmode="email" maxlength="255" title="Ievadiet derīgu e-pastu, kurā obligāti ir simbols @." required>
    </div>

    <!-- Telefona numurs ar rakstzīmju skaita indikāciju -->
    <div class="form-group">
        <label for="TelefonaNumurs" class="form-label">Telefona numurs:</label>
        <input type="text" class="form-control" value="{{ old('TelefonaNumurs', $klientis->TelefonaNumurs) }}" id="TelefonaNumurs" name="TelefonaNumurs" maxlength="8" inputmode="numeric" pattern="[0-9]{8}" title="Ievadiet tikai 8 ciparus." required>
        <div class="character-count" id="charCount">{{ strlen(old('TelefonaNumurs', $klientis->TelefonaNumurs)) }}/8</div> <!-- Rāda cik rakstzīmes ievadītas -->
    </div>   

    <!-- Uzņēmuma nosaukums -->
    <div class="form-group">
        <label for="UznemumaNosaukums">Uzņēmuma nosaukums:</label>
        <input type="text" class="form-control" id="UznemumaNosaukums" name="UznemumaNosaukums" value="{{ old('UznemumaNosaukums', $klientis->UznemumaNosaukums) }}" maxlength="30" data-letters-only="true" data-capitalize="words" autocomplete="off" title="Drīkst ievadīt tikai burtus, un pirmais burts būs liels." required>
    </div>

    <!-- Juridiskā adrese -->
    <div class="form-group">
        <label for="JuridiskaAdrese">Juridiskā adrese:</label>
        <input type="text" class="form-control" id="JuridiskaAdrese" name="JuridiskaAdrese" value="{{ old('JuridiskaAdrese', $klientis->JuridiskaAdrese) }}" maxlength="255" data-address-format="true" autocomplete="off" title="Drīkst ievadīt burtus un ciparus, un pirmajam burtam jābūt lielajam." required>
    </div>

    <!-- Reģistrācijas numurs -->
    <div class="form-group">
        <label for="RegistracijasNumurs">Reģistrācijas numurs:</label>
        <input type="text" class="form-control" id="RegistracijasNumurs" name="RegistracijasNumurs" value="{{ old('RegistracijasNumurs', $klientis->RegistracijasNumurs) }}" maxlength="11" inputmode="numeric" pattern="[0-9]{1,11}" title="Drīkst ievadīt tikai ciparus (maksimums 11)." required>
    </div>

    <!-- Konta numurs -->
    <div class="form-group">
        <label for="KontaNumurs">Konta numurs:</label>
        <input type="text" class="form-control" id="KontaNumurs" name="KontaNumurs" value="{{ old('KontaNumurs', $klientis->KontaNumurs) }}" maxlength="21" inputmode="text" pattern="LV[A-Za-z0-9]{0,19}" data-account-format="true" autocomplete="off" style="text-transform: uppercase;" title="Konta numuram jāsākas ar LV un maksimālais garums ir 21 simbols." required>
    </div>

    <!-- Saglabāšanas poga -->
    <button type="submit" 
        style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Atjaunināt
    </button>
</form>

<!-- JavaScript: klienta ieraksta datu validācija un lauku formatēšana uz vietas. -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const TelefonaNumursInput = document.getElementById('TelefonaNumurs');
    const RegistracijasNumursInput = document.getElementById('RegistracijasNumurs');
    const JuridiskaAdreseInput = document.getElementById('JuridiskaAdrese');
    const KontaNumursInput = document.getElementById('KontaNumurs');
    const charCount = document.getElementById('charCount');

    const normalizeLettersOnly = function(value) {
        const cleaned = value.replace(/[^\p{L}\s]/gu, '').replace(/\s+/g, ' ').trimStart();
        return cleaned.toLocaleLowerCase('lv-LV').replace(/(^|\s)\p{L}/gu, function(match) {
            return match.toLocaleUpperCase('lv-LV');
        });
    };

    // Normalizē juridiskās adreses lauku, atstājot tikai adreses raksturīgos simbolus.
    const normalizeAddress = function(value) {
        const cleaned = value.replace(/[^0-9\p{L}\s.,\-/]/gu, '').replace(/\s+/g, ' ').trimStart();
        if (!cleaned) {
            return '';
        }

        return cleaned.charAt(0).toLocaleUpperCase('lv-LV') + cleaned.slice(1);
    };

    // Formē konta numuru standartā: tikai LV kods un ciparu/teksta simboli.
    const normalizeAccountNumber = function(value) {
        const cleaned = value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (!cleaned) {
            return '';
        }

        if (cleaned.startsWith('LV')) {
            return cleaned.slice(0, 21);
        }

        return ('LV' + cleaned).slice(0, 21);
    };

    document.querySelectorAll('[data-letters-only="true"]').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = normalizeLettersOnly(this.value);
        });
    });

    if (TelefonaNumursInput && charCount) {
        TelefonaNumursInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 8);
            charCount.textContent = this.value.length + '/8';
        });
    }

    if (RegistracijasNumursInput) {
        RegistracijasNumursInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
    }

    if (JuridiskaAdreseInput) {
        JuridiskaAdreseInput.addEventListener('input', function() {
            this.value = normalizeAddress(this.value);
        });
    }

    if (KontaNumursInput) {
        KontaNumursInput.addEventListener('input', function() {
            this.value = normalizeAccountNumber(this.value);
        });
    }
});
</script>

<!-- CSS stili formas elementiem -->
<style>
.form-group {
    margin-bottom: 20px; /* Atstarpes starp laukiem */
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
    border-color: #C2CBD1;
    box-shadow: 0 0 5px rgba(89, 193, 207, 0.3);
}

button[type="submit"] {
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: transform 0.2s;
}

button[type="submit"]:hover {
    transform: scale(1.05); /* Neliela animācija uz hover */
}
</style>

@endsection <!-- Satura sadaļas beigas -->
