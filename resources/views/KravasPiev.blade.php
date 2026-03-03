
@extends('layout.app')

@section('content')
    <h2>Pievienot jaunus kravas datus</h2>
    <a href="/Kravas"  style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Atpakaļ</a>

    <hr>

    <form method="POST" action="/Krava/jaunsSubmit">
        @csrf
        <div class="form-group">
            <label for="Nosaukums">Nosaukums:</label>
            <input type="text" class="form-control" id="Nosaukums" name="Nosaukums" required>
        </div>

        <button type="submit" style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">Saglabāt</button>
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

