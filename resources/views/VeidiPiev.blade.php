@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<h2>Pievienot jaunus veidu datus</h2> <!-- Virsraksts lapai -->

<a href="/Veidi" style="border-radius:8px; border: 1px solid #59c1cf; 
        padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
    Atpakaļ
</a> <!-- Poga atpakaļ uz veidu sarakstu -->

<hr> <!-- Horizontālā līnija -->

<form method="POST" action="/Veidi/jaunsSubmit">
    @csrf <!-- CSRF aizsardzība -->

    <div class="form-group">
        <label for="Nosaukums">Nosaukums:</label> <!-- Etiķete ievades laukam -->
        <input type="text" class="form-control" id="Nosaukums" name="Nosaukums" required>
        <!-- Teksta lauks jauna veida nosaukumam, obligāts aizpildīt -->
    </div>

    <button type="submit" style="border-radius:8px; border: 1px solid #59c1cf; 
            padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Saglabāt
    </button> <!-- Poga jauna veida saglabāšanai -->
</form>

<style>
    .form-group { margin-bottom: 20px; } <!-- Atstarpes starp formu laukiem -->
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; } <!-- Etiķetes stils -->
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; } <!-- Ievades lauka stils -->
    .form-control:focus { outline: none; border-color: #59c1cf; box-shadow: 0 0 5px rgba(89, 193, 207, 0.3); } <!-- Fokusēta lauka efekts -->
    button[type="submit"] { cursor: pointer; font-size: 16px; font-weight: 500; transition: transform 0.2s; } <!-- Poga stils -->
    button[type="submit"]:hover { transform: scale(1.05); } <!-- Poga hover efekts -->
</style>

@endsection <!-- Satura sadaļa beidzas -->