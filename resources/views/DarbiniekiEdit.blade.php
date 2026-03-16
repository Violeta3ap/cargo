@extends('layout.app') <!-- Paplašina galveno layout failu -->

@section('content') <!-- Satura sadaļa -->

<h2>Rediģēt darbinieku</h2> <!-- Virsraksts -->

<!-- Poga atpakaļ uz darbinieku sarakstu -->
<a href="/Darbinieki"
   style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a>

<hr> <!-- Atstarpes līnija -->

<!-- Rediģēšanas forma -->
<form action="/Darbinieki/{{ $darbinieki->DarbiniekaID }}/editSubmit" method="POST">
    @csrf <!-- Aizsardzība pret CSRF -->

    <!-- Vārds -->
    <div class="form-group">
        <label for="Vards">Vārds:</label>
        <input type="text" class="form-control" id="Vards" name="Vards" value="{{ $darbinieki->Vards }}" required>
    </div>

    <!-- Uzvārds -->
    <div class="form-group">
        <label for="Uzvards">Uzvārds:</label>
        <input type="text" class="form-control" id="Uzvards" name="Uzvards" value="{{ $darbinieki->Uzvards }}" required>
    </div>

    <!-- Parole -->
    <div class="form-group">
        <label for="Parole">Parole:</label>
        <input type="password" class="form-control" id="Parole" name="Parole" value="{{ $darbinieki->Parole }}" required>
    </div>

    <!-- E-pasts -->
    <div class="form-group">
        <label for="Epasts">E-pasts:</label>
        <input type="email" class="form-control" id="Epasts" name="Epasts" value="{{ $darbinieki->Epasts }}" required>
    </div>

    <!-- Telefona numurs ar maksimālo rakstzīmju limitu un skaitītāju -->
    <div class="form-group">
        <label for="TelefonaNumurs" class="form-label">Telefona numurs:</label>
        <input type="text" class="form-control" value="{{ $darbinieki->TelefonaNumurs }}" id="TelefonaNumurs" name="TelefonaNumurs" maxlength="8" required>
        <div class="character-count" id="charCount">{{ strlen($darbinieki->TelefonaNumurs) }}/8</div> <!-- Rāda cik rakstzīmju aizpildīts -->
    </div>   

    <!-- Amata izvēle no dropdown -->
    <div class="form-group">
        <label for="AmataID">Amats:</label>
        <select class="form-control" id="AmataID" name="AmataID" required>
            @foreach($amati as $amats)
            <option value="{{ $amats->AmataID }}" 
                    {{ $darbinieki->AmataID == $amats->AmataID ? 'selected' : '' }}>
                {{ $amats->Nosaukums }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Poga saglabāt -->
    <button type="submit" style="border-radius:8px; border:1px solid #59c1cf; padding:5px; color:#000; text-decoration:none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Atjaunināt
    </button>
</form>

<!-- JS: parāda atlikušās rakstzīmes telefona laukā un maina krāsu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const TelefonaNumursInput = document.getElementById('TelefonaNumurs');
    const charCount = document.getElementById('charCount');
    if (TelefonaNumursInput && charCount) {
        TelefonaNumursInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength + '/8';

            // Krāsu maiņa atkarībā no garuma
            if (currentLength >= 8) {
                charCount.style.color = '#59c1cf';
            } else if (currentLength >= 5) {
                charCount.style.color = '#68e3f3';
            } else {
                charCount.style.color = '#e75480';
            }
        });
    }
});
</script>

<!-- Stils formām un pogām -->
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
    transform: scale(1.05); /* Nedaudz palielinās hover laikā */
}
</style>

@endsection <!-- Satura beigas -->