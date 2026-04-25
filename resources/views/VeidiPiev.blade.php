@extends('layout.app')

@section('content')

<h2>Pievienot jaunus vagonu veidu datus</h2>

<a href="/Veidi" style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
    Atpakaļ
</a>

<hr>

<form method="POST" action="/Veidi/jaunsSubmit">
    @csrf

    <div class="form-group">
        <label for="Nosaukums">Vagona veida nosaukums:</label>
        <input type="text" class="form-control" id="Nosaukums" name="Nosaukums" maxlength="30" pattern="[A-Za-zĀ-ž]+" title="Drīkst ievadīt tikai burtus." required>
    </div>

    <div class="form-group">
        <label for="Celtspeja">Celtspēja tonnās:</label>
        <input type="number" class="form-control" id="Celtspeja" name="Celtspeja" min="1" required>
    </div>

    <div class="form-group">
        <label for="VagonuSkaits">Vagonu skaits:</label>
        <input type="number" class="form-control" id="VagonuSkaits" name="VagonuSkaits" min="1" required>
    </div>

    <div class="form-group">
        <label for="CenaParDiennakti">Cena par diennakti:</label>
        <input type="number" class="form-control" id="CenaParDiennakti" name="CenaParDiennakti" step="0.01" min="0.01" required>
    </div>

    <button type="submit" style="border-radius:8px; border: 1px solid #C2CBD1; padding: 5px; color: #000000; text-decoration: none; background: linear-gradient(to right, #C2CBD1, #ffffff)">
        Saglabāt
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nosaukumsInput = document.getElementById('Nosaukums');

    if (nosaukumsInput) {
        nosaukumsInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\p{L}]/gu, '');
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

@endsection 