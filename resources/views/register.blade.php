<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8"> <!-- Karakteru kodējums -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsīvs dizains -->
    <title>Reģistrācija</title> <!-- Lapas nosaukums -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->

    <style>
        body { background-color: #ffffff; font-family: Arial, sans-serif; } <!-- Fona krāsa un fonts -->
        .center-wrap { display: flex; justify-content: center; align-items: center; height: 100vh; } <!-- Centrs vertikāli un horizontāli -->
        .card { width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); } <!-- Kartītes stils -->
        .btn-bloom { background-color: #59c1cf; color: #ffffff; border: none; border-radius: 8px; padding: 10px; font-size: 16px; } <!-- Poga stils -->
        .btn-bloom:hover { background-color: #59c1cf; } <!-- Hover efekts pogai -->
    </style>
</head>

<body>

<div class="center-wrap"> <!-- Centrs saturam -->
    <div class="card custom p-4"> <!-- Kartīte formai -->
        <h2 class="text-center mb-4">Reģistrācija</h2> <!-- Virsraksts -->

        <!-- Kļūdu paziņojumi -->
        @if ($errors->any())
            <div class="alert alert-danger"> <!-- Sarkans ziņojums -->
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li> <!-- Katra kļūda -->
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Veiksmīgas reģistrācijas paziņojums -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div> <!-- Zaļš ziņojums -->
        @endif

        <!-- Reģistrācijas forma -->
        <form method="POST" action="{{ url('/register') }}">
            @csrf <!-- CSRF aizsardzība -->

            <div class="mb-3">
                <label for="name" class="form-label">Lietotājvārds</label> <!-- Lietotājvārda etiķete -->
                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}"> <!-- Lietotājvārda lauks -->
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-pasts</label> <!-- E-pasta etiķete -->
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}"> <!-- E-pasta lauks -->
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Parole</label> <!-- Paroles etiķete -->
                <input type="password" class="form-control" id="password" name="password" required> <!-- Paroles lauks -->
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Apstiprini paroli</label> <!-- Paroles apstiprinājums -->
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required> <!-- Apstiprinājuma lauks -->
            </div>

            <button type="submit" class="btn btn-bloom w-100">Reģistrēties</button> <!-- Reģistrācijas poga -->
        </form>

        <div class="text-center mt-3">
            <a href="/" style="color: #59c1cf; text-decoration: none; font-size: 14px;">Atpakaļ uz sākumlapu</a> <!-- Atpakaļ uz mājaslapu -->
        </div>
    </div>
</div>

</body>
</html>