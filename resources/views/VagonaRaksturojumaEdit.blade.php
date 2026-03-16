@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->
    <h2>Rediģēt vagonu datus</h2> <!-- Virsraksts -->
    <a href="/VagonaRaksturojums"  style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a> <!-- Atpakaļ poga -->

    <hr> <!-- Horizontāla līnija -->

    <form action="/VagonaRaksturojums/{{ $vagonaraksturojums->VagonaID }}/editSubmit" method="POST"> <!-- Formas darbība -->
        @csrf <!-- CSRF aizsardzība -->

        <div class="form-group">
            <label for="VeidaID">Veida ID:</label>
            <input type="number" class="form-control" id="VeidaID" name="VeidaID" value="{{ $vagonaraksturojums->VeidaID }}" required> <!-- Veida ID lauks -->
        </div>

        <div class="form-group">
            <label for="KravasID">Kravas ID:</label>
            <input type="number" class="form-control" id="KravasID" name="KravasID" value="{{ $vagonaraksturojums->KravasID }}" required> <!-- Kravas ID lauks -->
        </div>

        <div class="form-group">
            <label for="Celtspeja">Celtspeja:</label>
            <input type="number" class="form-control" id="Celtspeja" name="Celtspeja" 
            value="{{ $vagonaraksturojums->Celtspeja }}" min="1" required> <!-- Celtspeja lauks -->
        </div>

        <div class="form-group">
            <label for="VagonaNumurs" class="form-label">Vagona numurs:</label>
            <input type="text" class="form-control" value="{{ $vagonaraksturojums->VagonaNumurs }}" id="VagonaNumurs" name="VagonaNumurs" maxlength="8" required> <!-- Vagona numurs -->
            <div class="character-count" id="charCount">{{ strlen($vagonaraksturojums->VagonaNumurs) }}/8</div> <!-- Rakstzīmju skaita indikators -->
        </div>    

        <button type="submit" style="border-radius:8px; border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff">Atjaunināt</button> <!-- Saglabāt poga -->
    </form>

    <script>
    // Parāda atlikušās rakstzīmes Vagona numura laukā
    document.addEventListener('DOMContentLoaded', function() {
        const VagonaNumursInput = document.getElementById('VagonaNumurs');
        const charCount = document.getElementById('charCount');
        if (VagonaNumursInput && charCount) {
            VagonaNumursInput.addEventListener('input', function() {
                const currentLength = this.value.length;
                charCount.textContent = currentLength + '/8'; // Atjaunina skaitu
                // Maina krāsu atkarībā no garuma
                if (currentLength >= 5) {
                    charCount.style.color = '#68e3f3';
                } else if (currentLength >= 8) {
                    charCount.style.color = '#59c1cf';
                } else {
                    charCount.style.color = '#e75480';
                }
            });
        }
    });
    </script>

    <style>
        .form-group { margin-bottom: 20px; } <!-- Atstarpe starp laukiem -->
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; } <!-- Label stils -->
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; } <!-- Ievades lauks stils -->
        .form-control:focus { outline: none; border-color: #59c1cf; box-shadow: 0 0 5px rgba(89, 193, 207, 0.3); } <!-- Fokusa efekts -->
        button[type="submit"] { cursor: pointer; font-size: 16px; font-weight: 500; transition: transform 0.2s; } <!-- Poga stils -->
        button[type="submit"]:hover { transform: scale(1.05); } <!-- Hover efekts -->
    </style>
@endsection <!-- Satura sadaļa beidzas -->