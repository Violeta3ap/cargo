@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Pievienot jaunu klientu</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz klientu sarakstu -->
<a href="/Klienti"  
   style="border-radius:8px; border: 1px solid #991C00; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #991C00, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Horizontāla līnija -->

@if($errors->has('duplicate'))
    <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 8px;">
        {{ $errors->first('duplicate') }}
    </div>
@endif

<!-- Forma jauna klienta pievienošanai -->
<form method="POST" action="/Klienti/jaunsSubmit">
    @csrf <!-- CSRF aizsardzība -->

    <!-- Vārds -->
    <div class="form-group">
        <label for="Vards">Vārds:</label>
        <input type="text" class="form-control" id="Vards" name="Vards" value="{{ old('Vards') }}" required>
    </div>

    <!-- Uzvārds -->
    <div class="form-group">
        <label for="Uzvards">Uzvārds:</label>
        <input type="text" class="form-control" id="Uzvards" name="Uzvards" value="{{ old('Uzvards') }}" required>
    </div>

    <!-- E-pasts -->
    <div class="form-group">
        <label for="Epasts">E-pasts:</label>
        <input type="email" class="form-control" id="Epasts" name="Epasts" value="{{ old('Epasts') }}" required>
    </div>

    <div class="form-group">
    <label for="TelefonaNumurs">Telefona numurs:</label>
    <input type="text" class="form-control" id="TelefonaNumurs" name="TelefonaNumurs" value="{{ old('TelefonaNumurs') }}" maxlength="8" required>
    
    <!-- Rāda simbolu skaitu -->
    <div class="character-count" id="charCount">{{ strlen(old('TelefonaNumurs', '')) }}/8</div>
</div>



    <!-- Uzņēmuma nosaukums -->
    <div class="form-group">
        <label for="UznemumaNosaukums">Uzņēmuma nosaukums:</label>
        <input type="text" class="form-control" id="UznemumaNosaukums" name="UznemumaNosaukums" value="{{ old('UznemumaNosaukums') }}" required>
    </div>

    <!-- Juridiskā adrese -->
    <div class="form-group">
        <label for="JuridiskaAdrese">Juridiskā adrese:</label>
        <input type="text" class="form-control" id="JuridiskaAdrese" name="JuridiskaAdrese" value="{{ old('JuridiskaAdrese') }}" required>
    </div>

    <!-- Reģistrācijas numurs -->
    <div class="form-group">
        <label for="RegistracijasNumurs">Reģistrācijas numurs:</label>
        <input type="text" class="form-control" id="RegistracijasNumurs" name="RegistracijasNumurs" value="{{ old('RegistracijasNumurs') }}" required>
    </div>

    <!-- Konta numurs -->
    <div class="form-group">
        <label for="KontaNumurs">Konta numurs:</label>
        <input type="text" class="form-control" id="KontaNumurs" name="KontaNumurs" value="{{ old('KontaNumurs') }}" required>
    </div>

    <!-- Saglabāšanas poga -->
    <button type="submit" 
        style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Saglabāt
    </button>
</form>




<script>
document.addEventListener('DOMContentLoaded', function() {
    const TelefonaNumursInput = document.getElementById('TelefonaNumurs');
    const charCount = document.getElementById('charCount');

    if (TelefonaNumursInput && charCount) {
        TelefonaNumursInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength + '/8';
        });
    }
});
</script>




<!-- CSS stili formas laukiem un pogām -->
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
    box-shadow: 0 0 5px rgba(194, 203, 209, 0.3);
}

button[type="submit"] {
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: transform 0.2s;
}

button[type="submit"]:hover {
    transform: scale(1.05); /* Neliela palielināšanās hover laikā */
}
</style>

@endsection <!-- Satura sadaļas beigas -->
