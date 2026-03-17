@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->
    <h2>Pievienot jaunus vagonu datus</h2> <!-- Virsraksts -->
    <a href="/VagonaRaksturojums" style="border-radius:8px; border: 1px solid #59c1cf; 
        padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Atpakaļ
    </a> <!-- Atpakaļ poga -->

    <hr> <!-- Horizontāla līnija -->

    <form method="POST" action="/VagonaRaksturojums/jaunsSubmit"> <!-- Formas darbība uz jaunu vagonu -->
        @csrf <!-- CSRF aizsardzība -->

        <!-- <div class="form-group">
            <label for="VeidaID">Veida ID:</label> 
            <input type="number" class="form-control" id="VeidaID" name="VeidaID" min="1" required> 
        </div> -->


        <div class="form-group">
    <label for="VeidaID">Veids:</label>
    <select class="form-control" name="VeidaID" required>
        @foreach($veidi as $veids)
            <option value="{{ $veids->VeidaID }}">
                {{ $veids->Nosaukums }}
            </option>
        @endforeach
    </select>
</div>




        <!-- <div class="form-group">
            <label for="KravasID">Kravas ID:</label> 
            <input type="number" class="form-control" id="KravasID" name="KravasID" min="1" required> 
        </div> -->

        <div class="form-group">
    <label for="KravasID">Krava:</label>
    <select class="form-control" name="KravasID" required>
        @foreach($kravas as $krava)
            <option value="{{ $krava->KravasID }}">
                {{ $krava->Nosaukums }}
            </option>
        @endforeach
    </select>
</div>

        <div class="form-group">
            <label for="Celtspeja">Celtspeja:</label> <!-- Celtspeja lauks -->
            <input type="number" class="form-control" id="Celtspeja" name="Celtspeja" min="1" required> <!-- Ievades lauks ar minimum vērtību -->
        </div>

        <!-- <div class="form-group">
            <label for="VagonaNumurs">Vagona Numurs:</label>
            <input type="text" class="form-control" id="VagonaNumurs" name="VagonaNumurs" min="1" required> 
        </div> -->



        <div class="form-group">
    <label for="VagonaNumurs">Vagona numurs:</label>
    <input type="text" class="form-control" id="VagonaNumurs" name="VagonaNumurs" maxlength="8" required>
    
    <!-- Rāda simbolu skaitu -->
    <div class="character-count" id="charCount">0/8</div>
</div>


        <button type="submit" style="border-radius:8px; border: 1px solid #59c1cf; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
            Saglabāt
        </button> 
    </form>


        <script>
// Opcionāls JavaScript, lai parādītu atlikušo rakstzīmju skaitu koda laukā
document.addEventListener('DOMContentLoaded', function() {
    const VagonaNumursInput = document.getElementById('VagonaNumurs');
    const charCount = document.getElementById('charCount');
    if (VagonaNumursInput && charCount) {
        VagonaNumursInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength + '/8';
        });
    }
});
</script>



<style>
    .form-group { margin-bottom: 20px; } 
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; } 
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; } 
    .form-control:focus { outline: none; border-color: #59c1cf; box-shadow: 0 0 5px rgba(89, 193, 207, 0.3); } 
    button[type="submit"] { cursor: pointer; font-size: 16px; font-weight: 500; transition: transform 0.2s; } 
    button[type="submit"]:hover { transform: scale(1.05); } 
</style>
@endsection <!-- Satura sadaļa beidzas -->