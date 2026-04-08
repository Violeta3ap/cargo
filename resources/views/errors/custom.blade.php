<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kļūda | CARGO</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #f5fbfd 0%, #ffffff 100%);
            color: #1f2937;
        }
        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            width: min(560px, 100%);
            background: #ffffff;
            border: 1px solid #c2cbd1;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .card-header {
            background: #5985a8;
            color: #fff;
            padding: 18px 22px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        .card-body {
            padding: 24px 22px;
        }
        .badge {
            display: inline-block;
            margin-bottom: 10px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e8f5f7;
            color: #1d5f75;
            font-size: 0.9rem;
            font-weight: 700;
        }
        h1 {
            margin: 0 0 10px;
            font-size: 1.7rem;
        }
        p {
            margin: 0;
            line-height: 1.55;
            color: #374151;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }
        .btn {
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #c2cbd1;
            color: #000;
            background: linear-gradient(to right, #c2cbd1, #ffffff);
        }
        .btn-primary {
            border-color: #5985a8;
            background: linear-gradient(to right, #5985a8, #ffffff);
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="card-header">LDZ CARGO</div>
            <div class="card-body">
                <div class="badge">Kļūda {{ $status ?? 500 }}</div>
                <h1>{{ $title ?? 'Radās kļūda' }}</h1>
                <p>{{ $message ?? 'Pieprasījumu šobrīd nevar apstrādāt. Lūdzu, mēģiniet vēlreiz.' }}</p>

                <div class="actions">
                    <a href="{{ url()->previous() && url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn">Atpakaļ</a>
                    <a href="/" class="btn btn-primary">Uz sākumlapu</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
