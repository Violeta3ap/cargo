<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>CARGO</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if(Auth::check())
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
    @endif

    <style>
        /* Vispārējie iestatījumi lapas ķermenim */
        body {
            margin: 0; /* no margin */
            font-family: Arial, sans-serif; /* fonta stils */
            background-color: #ffffff; /* lapas fons */
        }

        /* Attēlu pielāgošana */
        img {
            max-width: 100%; /* nepārsniegt konteineru */
            height: auto;    /* automātiski augstums */
        }

        /* Galvenes stils */
        .header {
            background-color: rgb(195, 227, 238); /* fonā krāsa */
            color: white;
            padding: 10px 20px;
            font-weight: bold;
        }

        /* Navigācijas josla */
        .navigacija {
            background-color: #59c1cf;
            padding: 8px 10px;
            display: flex;
            gap: 80px; /* attālums starp saitēm */
            align-items: center;
            justify-content: center;
        }

        /* Navigācijas saites stils */
        .navigacija a {
            text-decoration: none;  
            background: linear-gradient(to right, #59c1cf, #ffffff);
            border: 1px solid #59c1cf;
            padding: 6px 14px;
            border-radius: 10px;
            color: black;
            font-size: 14px;
            gap: 20px;  
        }

        .navigacija a:hover {
            background-color: #59c1cf; /* hover efekts */
        }

        /* Galvenā satura konteiners */
        .content {  
            padding: 20px 150px; /* attālums no malas */
            background-color: #ffffff;
            min-height: 200px;
        }

        /* Footer stils */
        footer {
            background-color: #59c1cf;
            color: #000000;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            font-size: 0.95rem;
            position: fixed; /* vienmēr pie apakšas */
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            gap: 30px;  
        }

        /* Papildus navigācijas stils (var tikt izmantots citur) */
        .navigacijaa {
            background-color: #59c1cf;
            padding: 8px 10px;
            display: flex;
            gap: 80px;
            align-items: center;
            justify-content: center;
        }

        .navigacijaa a {
            text-decoration: none;  
            background: linear-gradient(to right, #59c1cf, #ffffff);
            padding: 6px 14px;
            border-radius: 10px;
            color: black;
            font-size: 14px;
            gap: 20px;  
        }

        .navigacijaa a:hover {
            background-color: #59c1cf;
        }

        /* Galvenais saturs ar 50% platumu */
        .mainContent {
            float: left;
            width: 50%;
        }

        /* Responsīvais dizains mazākiem ekrāniem */
        @media screen and (max-width: 800px) {
            .mainContent {
                width: 100%; /* pilns platums mobilajās ierīcēs */
            }
        }

        /* Kopīgie paziņojumu stili tabulu lapām */
        .page-klienti .alert,
        .page-kravas .alert,
        .page-veidi .alert,
        .page-noma .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .page-klienti .alert-success,
        .page-kravas .alert-success,
        .page-veidi .alert-success,
        .page-noma .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .page-kravas .alert-danger,
        .page-veidi .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Klienti lapa */
        .page-klienti .klienti-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
            align-items: flex-end;
        }

        .page-klienti .filter-window {
            border: 1px solid #59c1cf;
            border-radius: 10px;
            padding: 10px;
            background: #f8fdfe;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
            width: 100%;
        }

        .page-klienti .filter-window h4 {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
        }

        .page-klienti .filter-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            align-items: center;
            overflow-x: auto;
        }

        .page-klienti .klienti-filter-form input {
            border: 1px solid #59c1cf;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.92rem;
            width: auto;
            box-sizing: border-box;
            flex: 0 0 220px;
        }

        .page-klienti .filter-btn {
            flex: 0 0 auto;
            padding: 4px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
            border: 1px solid #59c1cf;
            background-color: #59c1cf;
            color: #000;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .page-klienti .filter-btn:hover {
            background-color: #a2e0ed;
        }

            .page-print-noma .page-wrapper > div:first-child,
            .page-print-noma .navigacija,
            .page-print-noma .noma-filter-form,
            .page-print-noma .noma-pagination,
            .page-print-noma footer,
            .page-print-noma .alert,
            .page-print-noma .btn-action,
            .page-print-noma th:last-child,
            .page-print-noma td:last-child {
        .page-klienti .table thead th {
            border: 1px solid #59c1cf;
            padding: 12px;
            .page-print-noma h2 {
            position: relative;
        }

        .page-klienti .table thead th a.sort-link {
            .page-print-noma .table {
            text-decoration: none;
            display: inline-block;
            padding: 5px;
            transition: opacity 0.2s ease;
        }

            .page-print-noma .table thead {
            opacity: 0.8;
        }

        .page-klienti .table thead th .sort-icon {
            .page-print-noma .table thead th,
            .page-print-noma .table tbody td {
            font-size: 12px;
        }

        .page-klienti .table tbody tr:hover {
            .page-print-noma .table tbody tr:hover {
        }

        .page-klienti .table tbody td {
            .page-print-noma .table thead th a.sort-link {
            padding: 10px;
        }

        .page-klienti .klienti-pagination {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-klienti .page-btn {
            border-radius: 8px;
            border: 1px solid #59c1cf;
            padding: 6px 12px;
            color: #000000;
            text-decoration: none;
            background: linear-gradient(to right, #59c1cf, #ffffff);
            white-space: nowrap;
            font-size: 0.92rem;
            line-height: 1;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .page-klienti .page-btn.number {
            min-width: 34px;
            text-align: center;
            padding: 6px 10px;
        }

        .page-klienti .page-btn:hover {
            background: #a2e0ed;
            color: #000;
            transform: translateY(-1px);
        }

        .page-klienti .page-btn.active {
            background: #59c1cf;
            color: #000;
            font-weight: 600;
        }

        .page-klienti .page-btn.disabled {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Kravas lapa */
        .page-kravas .table {
            border-collapse: collapse;
        }

        .page-kravas .table thead {
            background-color: #59c1cf;
            color: white;
        }

        .page-kravas .table thead th {
            border: 1px solid #59c1cf;
            padding: 12px;
            font-weight: bold;
            position: relative;
        }

        .page-kravas .table thead th a.sort-link {
            color: white;
            text-decoration: none;
            display: inline-block;
            padding: 5px;
            transition: opacity 0.2s ease;
        }

        .page-kravas .table thead th a.sort-link:hover {
            opacity: 0.8;
        }

        .page-kravas .table thead th .sort-icon {
            display: inline-block;
            margin-left: 5px;
            font-size: 12px;
        }

        .page-kravas .table tbody tr:hover {
            background-color: #e8f5f7;
        }

        .page-kravas .table tbody td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .page-kravas .veidi-search-form input {
            border: 1px solid #59c1cf;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.92rem;
        }

        .page-kravas .filter-btn {
            flex: 0 0 auto;
            padding: 4px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
            border: 1px solid #59c1cf;
            background-color: #59c1cf;
            color: #000;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .page-kravas .filter-btn:hover {
            background-color: #a2e0ed;
        }

        .page-kravas .btn-action {
            border-radius: 8px;
            border: 1px solid #59c1cf;
            padding: 5px 10px;
            color: #000;
            text-decoration: none;
            background-color: #59c1cf;
            white-space: nowrap;
            text-align: center;
        }

        .page-kravas .btn-action:hover {
            background-color: #a2e0ed;
            color: #000;
        }

        /* Veidi lapa */
        .page-veidi .veidi-search-form input {
            border: 1px solid #59c1cf;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.92rem;
        }

        .page-veidi .filter-btn {
            flex: 0 0 auto;
            padding: 4px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
            border: 1px solid #59c1cf;
            background-color: #59c1cf;
            color: #000;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .page-veidi .filter-btn:hover {
            background-color: #a2e0ed;
        }

        .page-veidi .table {
            border-collapse: collapse;
        }

        .page-veidi .table thead {
            background-color: #59c1cf;
            color: white;
        }

        .page-veidi .table thead th {
            border: 1px solid #59c1cf;
            padding: 12px;
            font-weight: bold;
            position: relative;
        }

        .page-veidi .table thead th a.sort-link {
            color: white;
            text-decoration: none;
            display: inline-block;
            padding: 5px;
            transition: opacity 0.2s ease;
        }

        .page-veidi .table thead th a.sort-link:hover {
            opacity: 0.8;
        }

        .page-veidi .table thead th .sort-icon {
            display: inline-block;
            margin-left: 5px;
            font-size: 12px;
        }

        .page-veidi .table tbody tr:hover {
            background-color: #e8f5f7;
        }

        .page-veidi .table tbody td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .page-veidi .btn-action {
            transition: background-color 0.2s ease;
        }

        .page-veidi .btn-action:hover {
            background-color: #a2e0ed !important;
            color: #000;
        }

        /* Noma lapa */
        .page-noma .print-btn {
            border-radius: 8px;
            border: 1px solid #59c1cf;
            padding: 5px 10px;
            color: #000;
            text-decoration: none;
            background-color: #59c1cf;
            white-space: nowrap;
            margin-left: 8px;
        }

        .page-noma .print-btn:hover {
            background-color: #a2e0ed;
        }

        .page-noma .table {
            border-collapse: collapse;
        }

        .page-noma .noma-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
            align-items: flex-end;
        }

        .page-noma .filter-window {
            border: 1px solid #59c1cf;
            border-radius: 10px;
            padding: 10px;
            background: #f8fdfe;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
            width: 100%;
        }

        .page-noma .filter-window h4 {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
        }

        .page-noma .filter-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            align-items: center;
            overflow-x: auto;
        }

        .page-noma .noma-filter-form input {
            border: 1px solid #59c1cf;
            border-radius: 8px;
            padding: 4px 5px;
            font-size: 0.92rem;
            width: auto;
            box-sizing: border-box;
            flex: 0 0 190px;
        }

        .page-noma .filter-btn {
            flex: 0 0 auto;
            padding: 2px 8px;
            font-size: 0.8rem;
            border-radius: 6px;
            border: 1px solid #59c1cf;
            background-color: #59c1cf;
            color: #000;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .page-noma .filter-btn:hover {
            background-color: #a2e0ed;
        }

        .page-noma .table thead {
            background-color: #59c1cf;
            color: white;
        }

        .page-noma .table thead th {
            border: 1px solid #59c1cf;
            padding: 12px;
            font-weight: bold;
            position: relative;
        }

        .page-noma .table thead th a.sort-link {
            color: white;
            text-decoration: none;
            display: inline-block;
            padding: 5px;
            transition: opacity 0.2s ease;
        }

        .page-noma .table thead th a.sort-link:hover {
            opacity: 0.8;
        }

        .page-noma .table thead th .sort-icon {
            display: inline-block;
            margin-left: 5px;
            font-size: 12px;
        }

        .page-noma .table tbody tr:hover {
            background-color: #e8f5f7;
        }

        .page-noma .table tbody td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .page-noma .btn-action {
            border-radius: 8px;
            border: 1px solid #59c1cf;
            padding: 5px 10px;
            color: #000000;
            text-decoration: none;
            background-color: #59c1cf;
            white-space: nowrap;
            font-size: 0.9rem;
            width: 100%;
            text-align: center;
        }

        .page-noma .btn-action:hover {
            background-color: #a2e0ed;
            color: #000;
        }

        .page-noma .noma-pagination {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-noma .page-btn {
            border-radius: 8px;
            border: 1px solid #59c1cf;
            padding: 6px 12px;
            color: #000000;
            text-decoration: none;
            background: linear-gradient(to right, #59c1cf, #ffffff);
            white-space: nowrap;
            font-size: 0.92rem;
            line-height: 1;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .page-noma .page-btn.number {
            min-width: 34px;
            text-align: center;
            padding: 6px 10px;
        }

        .page-noma .page-btn:hover {
            background: #a2e0ed;
            color: #000;
            transform: translateY(-1px);
        }

        .page-noma .page-btn.active {
            background: #59c1cf;
            color: #000;
            font-weight: 600;
        }

        .page-noma .page-btn.disabled {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media print {
            .page-noma .page-wrapper > div:first-child,
            .page-noma .navigacija,
            .page-noma .noma-filter-form,
            .page-noma .noma-pagination,
            .page-noma footer,
            .page-noma .alert,
            .page-noma .btn-action,
            .page-noma th:last-child,
            .page-noma td:last-child {
                display: none !important;
            }

            .page-noma h2 {
                margin: 0 0 12px 0;
                font-size: 20px;
            }

            .page-noma .table {
                width: 100% !important;
                border: 1px solid #000;
                border-collapse: collapse;
                font-size: 12px;
            }

            .page-noma .table thead {
                background: #fff !important;
                color: #000 !important;
            }

            .page-noma .table thead th,
            .page-noma .table tbody td {
                border: 1px solid #000 !important;
                padding: 6px !important;
            }

            .page-noma .table tbody tr:hover {
                background: transparent !important;
            }

            .page-noma .table thead th a.sort-link {
                color: #000 !important;
                text-decoration: none;
            }
        }

    </style>
</head>
<body>

<div class="page-wrapper">

    <!-- Logo/virsraksts -->
    <div style="background: #ffffff; padding: 5px 5px; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);
         font-weight: bold; text-align: center;">
          LDZ CARGO
    </div>

    <!-- Navigācijas josla -->
    <div class="navigacija">

        @if(Auth::check()) <!-- ja lietotājs ir pieteicies -->
            <a href="/">Par uzņēmumu</a>

            @if(Auth::user()->isAdmin())
                {{-- Administrators redz visas saites --}}
                <a href="/Noma">Noma</a>
                <a href="/Klienti">Klienti</a>
                <a href="/Veidi">Vagonu veidi</a>
                <a href="/Kravas">Krāvu veidi</a>
            @elseif(Auth::user()->isDarbinieks())
                {{-- Darbinieks redz tikai pieļaujamās saites --}}
                <a href="/Noma">Noma</a>
                <a href="/Klienti">Klienti</a>
                <a href="/Veidi">Vagonu veidi</a>
                <a href="/Kravas">Krāvu veidi</a>
            @elseif(Auth::user()->isKlients())
                {{-- Klients redz tikai pieļaujamās saites --}}
                <a href="/Noma">Noma</a>
                <a href="/Veidi">Vagonu veidi</a>
                <a href="/Kravas">Krāvu veidi</a>
            @endif

            <span style="position: absolute; right: 120px; font-size: 13px;">{{ Auth::user()->name }}</span>
            <a href="/logout" style="position: absolute; right: 10px; border-radius:8px;  border: 1px solid #59c1cf; 
            padding: 5px; background:#ffffff; text-decoration: none; color: #000000;">Izlogoties</a>

        @else
            <a href="/">Par uzņēmumu</a>
            <a href="/Login" style="position: absolute; right: 10px; border-radius:8px;  border: 1px solid #59c1cf; 
            padding: 5px; background:#ffffff; text-decoration: none; color: #000000;">Ielogoties</a>
        @endif

    </div>


    <!-- Satura daļa -->
<div class="content" style="padding: 120px 10%;">
        @yield('content') <!-- šeit tiks ielādēts konkrētais lapas saturs -->
    </div>

    <!-- Footer -->
    <footer>
        <div>© 2014–2026 VAS "Latvijas dzelzceļš"</div>
        <div>Emīlijas Benjamiņas iela 3, Rīga, LV-1547</div>
        <div>Uzziņas: 8002 1181</div>
        <div>E-pasts: info@ldz.lv</div>
    </footer>

</div>

</body>
</html>
