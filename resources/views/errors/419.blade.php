<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kļūda 419 - Sesija beigusies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff; /* Fona krāsa */
            font-family: Arial, sans-serif; /* Fonts */
        }

        .center-wrap {
            display: flex; /* Flex centrs */
            justify-content: center; /* Horizontāli centrēts */
            align-items: center; /* Vertikāli centrēts */
            height: 100vh; /* Pilna ekrāna augstums */
        }

        .card {
            width: 400px; /* Kartes platums */
            padding: 20px; /* Iekšējais polsterējums */
            border-radius: 10px; /* Noapaļoti stūri */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ēnas efekts */
        }

        .btn-bloom {
            background-color: #59c1cf; /* Pogas fona krāsa */
            color: #ffffff; /* Pogas teksta krāsa */
            border: none; /* Bez malas */
            border-radius: 8px; /* Noapaļoti stūri */
            padding: 10px; /* Polsterējums */
            font-size: 16px; /* Teksta izmērs */
        }

        .btn-bloom:hover {
            background-color: #59c1cf; /* Hover efekts (paliek tāds pats) */
        }

        .error-icon {
            font-size: 48px;
            color: #dc3545; /* Sarkana krāsa kļūdai */
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="center-wrap">
        <div class="card">
            <div class="error-icon">⚠️</div>
            <h2 class="text-center mb-3">Sesija beigusies</h2>
            <p class="text-center mb-4">Jūsu sesija ir beigusies drošības nolūkos. Lūdzu, atsvaidziniet lapu vai ielogojieties vēlreiz.</p>
            <div class="text-center">
                <button class="btn btn-bloom w-100" onclick="window.location.reload();">Atsvaidzināt lapu</button>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">Atgriezties uz ielogošanos</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>