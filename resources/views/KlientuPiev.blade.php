@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Pievienot jaunu klientu</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz klientu sarakstu -->
<a href="/Klienti"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Horizontāla līnija -->

<!-- Forma jauna klienta pievienošanai -->
<form method="POST" action="/Klienti/jaunsSubmit">
    @csrf <!-- CSRF aizsardzība -->

    <!-- Vārds -->
    <div class="form-group">
        <label for="Vards">Vārds:</label>
        <input type="text" class="form-control" id="Vards" name="Vards" required>
    </div>

    <!-- Uzvārds -->
    <div class="form-group">
        <label for="Uzvards">Uzvārds:</label>
        <input type="text" class="form-control" id="Uzvards" name="Uzvards" required>
    </div>

    <!-- Parole -->
    <div class="form-group">
        <label for="Parole">Parole:</label>
        <input type="password" class="form-control" id="Parole" name="Parole" required>
    </div>

    <!-- E-pasts -->
    <div class="form-group">
        <label for="Epasts">E-pasts:</label>
        <input type="email" class="form-control" id="Epasts" name="Epasts" required>
    </div>

    <div class="form-group">
    <label for="TelefonaNumurs">Telefona numurs:</label>
    <input type="text" class="form-control" id="TelefonaNumurs" name="TelefonaNumurs" maxlength="8" required>
    
    <!-- Rāda simbolu skaitu -->
    <div class="character-count" id="charCount">0/8</div>
</div>



    <!-- Uzņēmuma nosaukums -->
    <div class="form-group">
        <label for="UznemumaNosaukums">Uzņēmuma nosaukums:</label>
        <input type="text" class="form-control" id="UznemumaNosaukums" name="UznemumaNosaukums" required>
    </div>

    <!-- Juridiskā adrese -->
    <div class="form-group">
        <label for="JuridiskaAdrese">Juridiskā adrese:</label>
        <input type="text" class="form-control" id="JuridiskaAdrese" name="JuridiskaAdrese" required>
    </div>

    <!-- Reģistrācijas numurs -->
    <div class="form-group">
        <label for="RegistracijasNumurs">Reģistrācijas numurs:</label>
        <input type="text" class="form-control" id="RegistracijasNumurs" name="RegistracijasNumurs" required>
    </div>

    <!-- Konta numurs -->
    <div class="form-group">
        <label for="KontaNumurs">Konta numurs:</label>
        <input type="text" class="form-control" id="KontaNumurs" name="KontaNumurs" required>
    </div>

    <!-- Saglabāšanas poga -->
    <button type="submit" 
        style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
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
    transform: scale(1.05); /* Neliela palielināšanās hover laikā */
}
</style>

@endsection <!-- Satura sadaļas beigas -->