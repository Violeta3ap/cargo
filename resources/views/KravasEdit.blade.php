@extends('layout.app') <!-- Paplašina galveno layout failu 'layout.app' -->

@section('content') <!-- Satura sadaļas sākums -->

<h2>Rediģēt krāvu veidus</h2> <!-- Lapas virsraksts -->

<!-- Atpakaļ poga uz kravu sarakstu -->
<a href="/Kravas"  
   style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Horizontāla līnija -->

<!-- Forma kravas datu rediģēšanai -->
<form action="/Kravas/{{ $kravas->KravasID }}/editSubmit" method="POST">
    @csrf <!-- CSRF aizsardzība -->

    <!-- Kravas nosaukums -->
    <div class="form-group">
        <label for="Nosaukums">Kravas veida nosaukums:</label>
        <input type="text" class="form-control" id="Nosaukums" name="Nosaukums" value="{{ $kravas->Nosaukums }}" maxlength="255" pattern="[A-Za-zĀ-ž]+" title="Drīkst ievadīt tikai burtus." required>
    </div>

    <div class="form-group">
        <label for="VeidaID">Vagona veida nosaukums:</label>
        <select class="form-control" id="VeidaID" name="VeidaID" required>
            @foreach($veidi as $veids)
                <option value="{{ $veids->VeidaID }}" {{ $veids->VeidaID == $kravas->VeidaID ? 'selected' : '' }}>
                    {{ $veids->Nosaukums }}
                </option>
            @endforeach
        </select>
    </div>


    <!-- Saglabāšanas poga -->
    <button type="submit" 
        style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Atjaunināt
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nosaukumsInput = document.getElementById('Nosaukums');

    if (nosaukumsInput) {
        nosaukumsInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\p{L}]/gu, '');
        });
    }
});
</script>

<!-- CSS stili formas elementiem un pogai -->
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
