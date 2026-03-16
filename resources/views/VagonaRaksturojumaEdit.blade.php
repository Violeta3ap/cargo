@extends('layout.app')

@section('content')
    <h2>Rediģēt vagonu datus</h2>
    <a href="/VagonaRaksturojums"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>


    <form action="/VagonaRaksturojums/{{ $vagonaraksturojums->VagonaID }}/editSubmit" method="POST">
        @csrf
        <div class="form-group">
            <label for="VeidaID">Veida ID:</label>
            <input type="number" class="form-control" id="VeidaID" name="VeidaID" value="{{ $vagonaraksturojums->VeidaID }}" required>
        </div>

        <div class="form-group">
            <label for="KravasID">Kravas ID:</label>
            <input type="number" class="form-control" id="KravasID" name="KravasID" value="{{ $vagonaraksturojums->KravasID }}" required>
        </div>

        <div class="form-group">
            <label for="Celtspeja">Celtspeja:</label>
            <input type="number" class="form-control" id="Celtspeja" name="Celtspeja" value="{{ $vagonaraksturojums->Celtspeja }}" required>
        </div>
<!-- 
        <div class="form-group">
            <label for="VagonaNumurs">Vagona Numurs:</label>
            <input type="text" class="form-control" id="VagonaNumurs" name="VagonaNumurs" value="{{ $vagonaraksturojums->VagonaNumurs }}" required>
        </div> -->

                <div class="form-group">
            <label for="VagonaNumurs" class="form-label">Vagona numurs:</label>
            <input type="text" class="form-control" value="{{ $vagonaraksturojums->VagonaNumurs }}" id="VagonaNumurs" name="VagonaNumurs" maxlength="6" required>
            <div class="character-count" id="charCount">{{ strlen($vagonaraksturojums->VagonaNumurs) }}/6</div>
        </div>    




        <button type="submit" style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atjaunināt</button>
    </form>


    <script>
// Opcionāls JavaScript, lai parādītu atlikušo rakstzīmju skaitu koda laukā
document.addEventListener('DOMContentLoaded', function() {
    const VagonaNumursInput = document.getElementById('VagonaNumurs');
    const charCount = document.getElementById('charCount');
    if (VagonaNumursInput && charCount) {
        VagonaNumursInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength + '/6';
            // Maina krāsu, ja tuvojas limitam
            if (currentLength >= 4) {
                charCount.style.color = '#68e3f3';
            } else if (currentLength >= 6) {
                charCount.style.color = '#59c1cf';
            } else {
                charCount.style.color = '#e75480';
            }
        });
    }
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
</style>
@endsection
