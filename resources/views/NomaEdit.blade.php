@extends('layout.app')

@section('content')
    <h2>Rediģēt nomu</h2>
    <a href="/Noma"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/lv.js"></script>

    <form action="/Noma/{{ $noma->NomasID }}/editSubmit" method="POST">
        @csrf
        <!-- <div class="form-group">
            <label for="KlientaID">Klienta ID:</label>
            <input type="number" class="form-control" id="KlientaID" name="KlientaID" value="{{ $noma->KlientaID }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="DarbiniekaID">Darbinieka ID:</label>
            <input type="number" class="form-control" id="DarbiniekaID" name="DarbiniekaID" value="{{ $noma->DarbiniekaID }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="KravasID">Kravas ID:</label>
            <input type="number" class="form-control" id="KravasID" name="KravasID" value="{{ $noma->KravasID }}" min="1" required>
        </div> -->


        <div class="form-group">
    <label for="KlientaID">Klients:</label>
    <select class="form-control" id="KlientaID" name="KlientaID" required>
        @foreach($klienti as $klientis)
            <option value="{{ $klientis->KlientaID }}"
                {{ $klientis->KlientaID == $noma->KlientaID ? 'selected' : '' }}>
                {{ $klientis->Vards }} {{ $klientis->Uzvards }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="DarbiniekaID">Darbinieks:</label>
    <select class="form-control" id="DarbiniekaID" name="DarbiniekaID" required>
        @foreach($darbinieki as $darbinieks)
            <option value="{{ $darbinieks->DarbiniekaID }}"
                {{ $darbinieks->DarbiniekaID == $noma->DarbiniekaID ? 'selected' : '' }}>
                {{ $darbinieks->Vards }} {{ $darbinieks->Uzvards }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="KravasID">Krava:</label>
    <select class="form-control" id="KravasID" name="KravasID" required>
        @foreach($kravas as $krava)
            <option value="{{ $krava->KravasID }}"
                {{ $krava->KravasID == $noma->KravasID ? 'selected' : '' }}>
                {{ $krava->Nosaukums }}
            </option>
        @endforeach
    </select>
</div>



        <div class="form-group">
            <label for="VagonuSkaits">Vagonu skaits:</label>
            <input type="number" class="form-control" id="VagonuSkaits" name="VagonuSkaits" value="{{ $noma->VagonuSkaits }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="NomasSakumaPeriods">Nomas sākuma periods:</label>
            <input type="date" class="form-control" id="NomasSakumaPeriods" name="NomasSakumaPeriods" value="{{ $noma->NomasSakumaPeriods }}" required>
        </div>

        <div class="form-group">
            <label for="NomasBeiguPeriods">Nomas beigu periods:</label>
            <input type="date" class="form-control" id="NomasBeiguPeriods" name="NomasBeiguPeriods" value="{{ $noma->NomasBeiguPeriods }}" required>
        </div>

        <div class="form-group">
            <label for="NosutisanasStacija">Nosūtīšanas stacija:</label>
            <input type="text" class="form-control" id="NosutisanasStacija" name="NosutisanasStacija" value="{{ $noma->NosutisanasStacija }}" required>
        </div>
        
        <div class="form-group">
            <label for="Galastacija">Gala stacija:</label>
            <input type="text" class="form-control" id="Galastacija" name="Galastacija" value="{{ $noma->Galastacija }}" required>
        </div>

        <div class="form-group">
            <label for="KopejaMaksa">Kopēja maksa:</label>
            <input type="number" class="form-control" id="KopejaMaksa" name="KopejaMaksa" value="{{ $noma->KopejaMaksa }}" min="1" required>
        </div>

        <button type="submit" style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atjaunināt</button>
    </form>

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



<script>
flatpickr("#NomasSakumaPeriods", {
    locale: "lv",
    dateFormat: "Y-m-d"
});

flatpickr("#NomasBeiguPeriods", {
    locale: "lv",
    dateFormat: "Y-m-d"
});
</script>


@endsection
