@extends('layout.app') <!-- Paplašina galveno izkārtojuma failu -->

@section('content') <!-- Satura daļa, kas tiks ielādēta layout -->

    <h2>Pievienot jaunus amata datus</h2> <!-- Virsraksts -->

    <!-- Poga atpakaļ uz amata sarakstu -->
    <a href="/Amati" style="border-radius:8px; border: 1px solid #C2CBD1; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Atpakaļ
    </a>

    <hr> <!-- Atstarpes līnija -->

    <!-- Forma jauna amata pievienošanai -->
    <form method="POST" action="/Amati/jaunsSubmit">
        @csrf <!-- CSRF aizsardzība -->

        <!-- Nosaukuma ievades lauks -->
        <div class="form-group">
            <label for="Nosaukums">Nosaukums:</label> <!-- Etiķete -->
            <input type="text" class="form-control" id="Nosaukums" name="Nosaukums" required> <!-- Teksta lauks -->
        </div>

        <!-- Saglabāšanas poga -->
        <button type="submit" style="border-radius:8px; border: 1px solid #C2CBD1; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
            Saglabāt
        </button>
    </form>

<style>
    /* Atstarpes starp formām */
    .form-group {
        margin-bottom: 20px;
    }

    /* Etiķešu stils */
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    /* Teksta lauka stils */
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    /* Teksta lauka fokusēšanas efekts */
    .form-control:focus {
        outline: none;
        border-color: #59c1cf;
        box-shadow: 0 0 5px rgba(89, 193, 207, 0.3);
    }

    /* Pogas stils */
    button[type="submit"] {
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: transform 0.2s;
    }

    /* Pogas hover efekts */
    button[type="submit"]:hover {
        transform: scale(1.05);
    }
</style>

@endsection