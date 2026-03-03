@extends('layout.app')

@section('content')
    <h2>Rediģēt Klientu</h2>
    <a href="/Klienti"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    <form action="/Klienti/{{ $klientis->KlientaID }}/editSubmit" method="POST">
        @csrf
        <div class="form-group">
            <label for="Vards">Vārds:</label>
            <input type="text" class="form-control" id="Vards" name="Vards" value="{{ $klientis->Vards }}" required>
        </div>

        <div class="form-group">
            <label for="Uzvards">Uzvārds:</label>
            <input type="text" class="form-control" id="Uzvards" name="Uzvards" value="{{ $klientis->Uzvards }}" required>
        </div>

        <div class="form-group">
            <label for="Parole">Parole:</label>
            <input type="password" class="form-control" id="Parole" name="Parole" value="{{ $klientis->Parole }}" required>
        </div>

        <div class="form-group">
            <label for="Epasts">E-pasts:</label>
            <input type="email" class="form-control" id="Epasts" name="Epasts" value="{{ $klientis->Epasts }}" required>
        </div>

        <div class="form-group">
            <label for="TelefonaNumurs">Telefona numurs:</label>
            <input type="text" class="form-control" id="TelefonaNumurs" name="TelefonaNumurs" value="{{ $klientis->TelefonaNumurs }}" required>
        </div>

        
                        <div class="form-group">
            <label for="UznemumaNosaukums">Uzņēmuma nosaukums:</label>
            <input type="text" class="form-control" id="UznemumaNosaukums" name="UznemumaNosaukums" value="{{ $klientis->UznemumaNosaukums }}" required>
        </div>

        <div class="form-group">
            <label for="JuridiskaAdrese">Juridiskā adrese:</label>
            <input type="text" class="form-control" id="JuridiskaAdrese" name="JuridiskaAdrese" value="{{ $klientis->JuridiskaAdrese }}" required>
        </div>

        <div class="form-group">
            <label for="RegistracijasNumurs">Reģistrācijas numurs:</label>
            <input type="text" class="form-control" id="RegistracijasNumurs" name="RegistracijasNumurs" value="{{ $klientis->RegistracijasNumurs }}" required>
        </div>

        <div class="form-group">
            <label for="KontaNumurs">Konta numurs:</label>
            <input type="text" class="form-control" id="KontaNumurs" name="KontaNumurs" value="{{ $klientis->KontaNumurs }}" required>
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
@endsection
