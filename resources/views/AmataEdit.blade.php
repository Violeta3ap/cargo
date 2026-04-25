@extends('layout.app')

@section('content')

    <h2>Rediģēt amata datus</h2> 

    
    <a href="/Amati" style="border-radius:8px; border: 1px solid #C2CBD1; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Atpakaļ
    </a>

    <hr> 

    
    <form action="/Amati/{{ $amati->AmataID }}/editSubmit" method="POST">
        @csrf 

        
        <div class="form-group">
            <label for="Nosaukums">Nosaukums:</label> 
            <input type="text" class="form-control" id="Nosaukums" name="Nosaukums" value="{{ $amati->Nosaukums }}" required> 
        </div>

        
        <button type="submit" style="border-radius:8px; border: 1px solid #C2CBD1; 
                padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
            Atjaunināt
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

    /* Teksta lauku stils */
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