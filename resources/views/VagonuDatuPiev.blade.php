@extends('layout.app') <!-- Paplašina galveno layout -->

@section('content') <!-- Satura sadaļa sākas -->

<h2>Pievienot jaunus vagonu datus</h2> <!-- Virsraksts lapai -->

<a href="/VagonuDati"  
   style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
   Atpakaļ
</a> <!-- Poga, lai atgrieztos pie vagonu datu saraksta -->

<hr> <!-- Horizontāla līnija, lai atdalītu -->

<form method="POST" action="/VagonuDati/jaunsSubmit"> <!-- Forma jauna ieraksta pievienošanai -->
    @csrf <!-- CSRF aizsardzība -->

    <div class="form-group">
        <label for="NomasID">Nomas ID:</label> <!-- Etiķete Nomas ID laukam -->
        <input type="number" class="form-control" id="NomasID" name="NomasID" min="1" required> <!-- Ievades lauks Nomas ID -->
    </div>

    <div class="form-group">
        <label for="VagonaID">Vagona ID:</label> <!-- Etiķete Vagona ID laukam -->
        <input type="number" class="form-control" id="VagonaID" name="VagonaID" min="1" required> <!-- Ievades lauks Vagona ID -->
    </div>

    <button type="submit" style="border-radius:8px; border: 1px solid #59c1cf; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #59c1cf, #ffffff)">
        Saglabāt
    </button> <!-- Poga, lai saglabātu jauno ierakstu -->
</form>

<style>
    .form-group {
        margin-bottom: 20px; /* Atstarpe starp lauka blokiem */
    }

    .form-group label {
        display: block; /* Etiķete uz jaunas rindas */
        margin-bottom: 8px; /* Atstarpe zem etiķetes */
        font-weight: 500; /* Teksta biezums */
        color: #333; /* Teksta krāsa */
    }

    .form-control {
        width: 100%; /* Aizņem visu platumu */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid #ddd; /* Robežas krāsa */
        border-radius: 4px; /* Noapaļoti stūri */
        font-size: 14px; /* Teksta izmērs */
        box-sizing: border-box; /* Iekšējā atstarpe iekļauta izmērā */
    }

    .form-control:focus {
        outline: none; /* Noņem noklusējuma kontūru */
        border-color: #59c1cf; /* Maina robežu krāsu fokusā */
        box-shadow: 0 0 5px rgba(89, 193, 207, 0.3); /* Viegls ēnojums fokusā */
    }

    button[type="submit"] {
        cursor: pointer; /* Rādītājs mainās uz roku */
        font-size: 16px; /* Teksta izmērs */
        font-weight: 500; /* Teksta biezums */
        transition: transform 0.2s; /* Animācija uz hover */
    }

    button[type="submit"]:hover {
        transform: scale(1.05); /* Nedaudz palielina pogu hover laikā */
    }
</style>

@endsection <!-- Satura sadaļa beidzas -->