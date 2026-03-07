<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reģistrācija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
            font-family: Arial, sans-serif;
        }

        .center-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-bloom {
            background-color: #59c1cf;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
        }

        .btn-bloom:hover {
            background-color: #59c1cf;
        }
    </style>
</head>

<body>

    <div class="center-wrap">
        <div class="card custom p-4">
            <h2 class="text-center mb-4">Reģistrācija</h2>

            <!-- Kļūdu paziņojumi -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ziņojums par veiksmīgu reģistrāciju -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Reģistrācijas forma -->
            <form method="POST" action="{{ url('/register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Lietotājvārds</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-pasts</label>
                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Parole</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Apstiprini paroli</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-bloom w-100">Reģistrēties</button>
            </form>

            <div class="text-center mt-3">
                <a href="/">Atpakaļ uz sākumlapu</a>
            </div>
        </div>
    </div>

</body>
</html>
