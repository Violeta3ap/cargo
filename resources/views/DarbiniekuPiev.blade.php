@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->

<h2>Pievienot jaunu darbinieku</h2> <!-- Virsraksts -->

<!-- Poga atpakaļ uz darbinieku sarakstu -->
<a href="/Darbinieki" style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background:linear-gradient(to right, #59c1cf, #ffffff)">
    Atpakaļ
</a>

<hr> <!-- Atstarpes līnija -->

<!-- Forma jaunam darbiniekam -->
<form method="POST" action="/Darbinieki/jaunsSubmit">
    @csrf <!-- Aizsardzība pret CSRF -->

    <!-- Vārda lauks -->
    <div class="form-group">
        <label for="Vards">Vārds:</label>
        <input type="text" class="form-control" id="Vards" name="Vards" required>
    </div>

    <!-- Uzvārda lauks -->
    <div class="form-group">
        <label for="Uzvards">Uzvārds:</label>
        <input type="text" class="form-control" id="Uzvards" name="Uzvards" required>
    </div>

    <!-- Paroles lauks -->
    <div class="form-group">
        <label for="Parole">Parole:</label>
        <input type="password" class="form-control" id="Parole" name="Parole" required>
    </div>

    <!-- E-pasta lauks -->
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




    <!-- Amata izvēle dropdown -->
    <div class="form-group">
        <label for="AmataID">Amats:</label>
        <select class="form-control" id="AmataID" name="AmataID" required>
            <option value="">Izvēlieties amatu</option>
            @foreach($amati as $amats)
                <option value="{{ $amats->AmataID }}">
                    {{ $amats->Nosaukums }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Saglabāšanas poga -->
    <button type="submit" style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background:linear-gradient(to right, #59c1cf, #ffffff)">
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



<!-- Stils formām un pogām -->
<style>
.form-group { margin-bottom: 20px; } 
.form-group label { display:block; margin-bottom:8px; font-weight:500; color:#333; }
.form-control { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:14px; box-sizing:border-box; }
.form-control:focus { outline:none; border-color:#59c1cf; box-shadow:0 0 5px rgba(89,193,207,0.3); }
button[type="submit"] { cursor:pointer; font-size:16px; font-weight:500; transition:transform 0.2s; }
button[type="submit"]:hover { transform:scale(1.05); } 
</style>

@endsection <!-- Satura beigas -->