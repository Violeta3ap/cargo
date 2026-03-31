@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Rediģēt klientu</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz klientu sarakstu -->
<a href="/Klienti"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
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

    <!-- Vārds -->
    <div class="form-group">
        <label for="Vards">Vārds:</label>
        <input type="text" class="form-control" id="Vards" name="Vards" value="{{ old('Vards', $klientis->Vards) }}" required>
    </div>

    <!-- Uzvārds -->
    <div class="form-group">
        <label for="Uzvards">Uzvārds:</label>
        <input type="text" class="form-control" id="Uzvards" name="Uzvards" value="{{ old('Uzvards', $klientis->Uzvards) }}" required>
    </div>

    <!-- E-pasts -->
    <div class="form-group">
        <label for="Epasts">E-pasts:</label>
        <input type="email" class="form-control" id="Epasts" name="Epasts" value="{{ old('Epasts', $klientis->Epasts) }}" required>
    </div>

    <!-- Telefona numurs ar rakstzīmju skaita indikāciju -->
    <div class="form-group">
        <label for="TelefonaNumurs" class="form-label">Telefona numurs:</label>
        <input type="text" class="form-control" value="{{ old('TelefonaNumurs', $klientis->TelefonaNumurs) }}" id="TelefonaNumurs" name="TelefonaNumurs" maxlength="8" required>
        <div class="character-count" id="charCount">{{ strlen(old('TelefonaNumurs', $klientis->TelefonaNumurs)) }}/8</div> <!-- Rāda cik rakstzīmes ievadītas -->
    </div>   

    <!-- Uzņēmuma nosaukums -->
    <div class="form-group">
        <label for="UznemumaNosaukums">Uzņēmuma nosaukums:</label>
        <input type="text" class="form-control" id="UznemumaNosaukums" name="UznemumaNosaukums" value="{{ old('UznemumaNosaukums', $klientis->UznemumaNosaukums) }}" required>
    </div>

    <!-- Juridiskā adrese -->
    <div class="form-group">
        <label for="JuridiskaAdrese">Juridiskā adrese:</label>
        <input type="text" class="form-control" id="JuridiskaAdrese" name="JuridiskaAdrese" value="{{ old('JuridiskaAdrese', $klientis->JuridiskaAdrese) }}" required>
    </div>

    <!-- Reģistrācijas numurs -->
    <div class="form-group">
        <label for="RegistracijasNumurs">Reģistrācijas numurs:</label>
        <input type="text" class="form-control" id="RegistracijasNumurs" name="RegistracijasNumurs" value="{{ old('RegistracijasNumurs', $klientis->RegistracijasNumurs) }}" required>
    </div>

    <!-- Konta numurs -->
    <div class="form-group">
        <label for="KontaNumurs">Konta numurs:</label>
        <input type="text" class="form-control" id="KontaNumurs" name="KontaNumurs" value="{{ old('KontaNumurs', $klientis->KontaNumurs) }}" required>
    </div>

    <!-- Saglabāšanas poga -->
    <button type="submit" 
        style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Atjaunināt
    </button>
</form>

<!-- JavaScript: rāda atlikušo rakstzīmju skaitu telefona laukumā -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const TelefonaNumursInput = document.getElementById('TelefonaNumurs');
    const charCount = document.getElementById('charCount');

    if (TelefonaNumursInput && charCount) {
        TelefonaNumursInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength + '/8'; // rāda simbolu skaitu
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
    transform: scale(1.05); /* Neliela animācija uz hover */
}
</style>

@endsection <!-- Satura sadaļas beigas -->
