@extends('layout.app')

@section('content')
    <h2>Jauns darbinieks</h2>
    <a href="/Darbinieki" class="btn btn-primary">Atpakaļ</a>

    <hr>

    <form action="/Darbinieki/jaunsSubmit" method="POST">
        @csrf
        <div class="form-group">
            <label for="Vards">Vārds:</label>
            <input type="text" class="form-control" id="Vards" name="Vards" required>
        </div>

        <div class="form-group">
            <label for="Uzvards">Uzvārds:</label>
            <input type="text" class="form-control" id="Uzvards" name="Uzvards" required>
        </div>

        <div class="form-group">
            <label for="Parole">Parole:</label>
            <input type="password" class="form-control" id="Parole" name="Parole" required>
        </div>

        <div class="form-group">
            <label for="Epasts">E-pasts:</label>
            <input type="email" class="form-control" id="Epasts" name="Epasts" required>
        </div>

        <div class="form-group">
            <label for="TelefonaNumurs">Telefona numurs:</label>
            <input type="text" class="form-control" id="TelefonaNumurs" name="TelefonaNumurs" required>
        </div>

        <div class="form-group">
            <label for="AmataID">Amata ID:</label>
            <input type="number" class="form-control" id="AmataID" name="AmataID" required>
        </div>

        <button type="submit" class="btn btn-success">Pievienot</button>
    </form>
@endsection
