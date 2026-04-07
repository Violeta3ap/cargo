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
        /* ===== Base ===== */
        body { margin: 0; font-family: Arial, sans-serif; background-color: #ffffff; }
        img { max-width: 100%; height: auto; }
        .header { background-color: rgb(195, 227, 238); color: white; padding: 10px 20px; font-weight: bold; }
        .mainContent { float: left; width: 50%; }
        @media screen and (max-width: 800px) { .mainContent { width: 100%; } }

        /* ===== Navigācija ===== */
        .navigacija, .navigacijaa {
            background-color: #5985a8;
            padding: 8px 10px;
            display: flex;
            gap: 80px;
            align-items: center;
            justify-content: center;
        }
        .navigacija a, .navigacijaa a {
            text-decoration: none;
            background: linear-gradient(to right, #5985a8, #ffffff);
            padding: 6px 14px;
            border-radius: 10px;
            color: black;
            font-size: 14px;
        }
        .navigacija a { border: 1px solid #5985a8; }
        .navigacija a:hover, .navigacijaa a:hover { background-color: #5985a8; }

        /* ===== Izkārtojums ===== */
        .content { padding: 20px 150px; background-color: #ffffff; min-height: 200px; }
        footer {
            background-color: #5985a8;
            color: #000000;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            font-size: 0.95rem;
            position: fixed;
            bottom: 0; left: 0;
            width: 100%;
            z-index: 100;
            gap: 30px;
        }

        /* ===== Paziņojumi ===== */
        .page-klienti .alert, .page-kravas .alert, .page-veidi .alert, .page-noma .alert {
            padding: 12px; margin-bottom: 15px; border-radius: 8px;
        }
        .page-klienti .alert-success, .page-kravas .alert-success,
        .page-veidi .alert-success, .page-noma .alert-success {
            background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724;
        }
        .page-klienti .alert-danger, .page-kravas .alert-danger, .page-veidi .alert-danger, .page-noma .alert-danger {
            background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;
        }

        /* ===== Tabulas - kopīgie stili ===== */
        .page-klienti .table, .page-kravas .table, .page-veidi .table, .page-noma .table { border-collapse: collapse; }
        .page-klienti .table thead, .page-kravas .table thead,
        .page-veidi .table thead, .page-noma .table thead { background-color: #5985a8; color: white; }
        .page-klienti .table thead th, .page-kravas .table thead th,
        .page-veidi .table thead th, .page-noma .table thead th {
            border: 1px solid #5985a8; padding: 12px; font-weight: bold; position: relative;
        }
        .page-klienti .table thead th a.sort-link,
        .page-kravas .table thead th a.sort-link,
        .page-veidi .table thead th a.sort-link,
        .page-noma .table thead th a.sort-link {
            color: white; text-decoration: none; display: inline-block; padding: 5px; transition: opacity 0.2s ease;
        }
        .page-klienti .table thead th a.sort-link:hover,
        .page-kravas .table thead th a.sort-link:hover,
        .page-veidi .table thead th a.sort-link:hover,
        .page-noma .table thead th a.sort-link:hover { opacity: 0.8; }
        .page-klienti .table thead th .sort-icon, .page-kravas .table thead th .sort-icon,
        .page-veidi .table thead th .sort-icon, .page-noma .table thead th .sort-icon {
            display: inline-block; margin-left: 5px; font-size: 12px;
        }
        .page-klienti .table tbody tr:hover, .page-kravas .table tbody tr:hover,
        .page-veidi .table tbody tr:hover, .page-noma .table tbody tr:hover { background-color: #e8f5f7; }
        .page-klienti .table tbody td, .page-veidi .table tbody td, .page-noma .table tbody td {
            border: 1px solid #ddd; padding: 10px;
        }
        .page-kravas .table tbody td { border: 1px solid #ddd; padding: 8px; }

        /* ===== Filter poga - kopīgā ===== */
        .page-klienti .filter-btn, .page-kravas .filter-btn,
        .page-veidi .filter-btn, .page-noma .filter-btn {
            flex: 0 0 auto;
            font-size: 0.8rem;
            border-radius: 6px;
            border: 1px solid #b62100;
            background-color: #b62100;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .page-klienti .filter-btn, .page-kravas .filter-btn, .page-veidi .filter-btn { padding: 4px 12px; }
        .page-noma .filter-btn { padding: 2px 8px; }
        .page-klienti .filter-btn:hover, .page-kravas .filter-btn:hover,
        .page-veidi .filter-btn:hover, .page-noma .filter-btn:hover { background-color: #b62100; color: #fff; }

        /* ===== Meklēšanas ievade ===== */
        .page-kravas .veidi-search-form input, .page-veidi .veidi-search-form input {
            border: 1px solid #C2CBD1; border-radius: 8px; padding: 8px 10px; font-size: 0.92rem;
        }

        /* ===== btn-action ===== */
        .page-kravas .btn-action, .page-noma .btn-action {
            border-radius: 8px; border: 1px solid #C2CBD1; padding: 5px 10px;
            color: #000; text-decoration: none; background-color: #C2CBD1;
            white-space: nowrap; text-align: center;
        }
        .page-kravas .btn-action:hover, .page-noma .btn-action:hover { background-color: #C2CBD1; color: #000; }
        .page-noma .btn-action { font-size: 0.9rem; width: 100%; }
        .page-veidi .btn-action { transition: background-color 0.2s ease; }
        .page-veidi .btn-action:hover { background-color: #C2CBD1 !important; color: #000; }

        /* ===== Page-btn (klienti un noma) ===== */
        .page-klienti .page-btn, .page-noma .page-btn {
            border-radius: 8px; border: 1px solid #C2CBD1; padding: 6px 12px;
            color: #000000; text-decoration: none;
            background: linear-gradient(to right, #C2CBD1, #ffffff);
            white-space: nowrap; font-size: 0.92rem; line-height: 1;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .page-klienti .page-btn.number, .page-noma .page-btn.number {
            min-width: 34px; text-align: center; padding: 6px 10px;
        }
        .page-klienti .page-btn:hover, .page-noma .page-btn:hover {
            background: #C2CBD1; color: #000; transform: translateY(-1px);
        }
        .page-klienti .page-btn.active, .page-noma .page-btn.active { background: #C2CBD1; color: #000; font-weight: 600; }
        .page-klienti .page-btn.disabled, .page-noma .page-btn.disabled { opacity: 0.45; cursor: not-allowed; pointer-events: none; }

        /* ===== Klienti ===== */
        .page-klienti .klienti-filter-form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; align-items: flex-end; }
        .page-klienti .filter-window, .page-noma .filter-window {
            border: 1px solid #C2CBD1; border-radius: 10px; padding: 10px; background: #f8fdfe;
            display: flex; flex-direction: column; gap: 8px; align-items: stretch; width: 100%;
        }
        .page-klienti .filter-window h4, .page-noma .filter-window h4 { margin: 0 0 4px 0; font-size: 0.95rem; }
        .page-klienti .filter-row { display: flex; flex-wrap: nowrap; gap: 10px; align-items: center; overflow-x: auto; }
        .page-klienti .klienti-filter-form input {
            border: 1px solid #C2CBD1; border-radius: 8px; padding: 8px 10px;
            font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 220px;
        }
        .page-klienti .klienti-pagination, .page-noma .noma-pagination {
            display: flex; gap: 6px; align-items: center; flex-wrap: wrap; justify-content: center;
        }

        /* ===== Noma ===== */
        .page-noma .print-btn {
            border-radius: 8px; border: 1px solid #C2CBD1; padding: 5px 10px;
            color: #000; text-decoration: none; background-color: #C2CBD1; white-space: nowrap; margin-left: 8px;
        }
        .page-noma .print-btn:hover { background-color: #C2CBD1; }
        .page-noma .noma-filter-form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; align-items: flex-end; }
        .page-noma .filter-row { display: flex; flex-wrap: nowrap; gap: 8px; align-items: center; overflow-x: auto; }
        .page-noma .noma-filter-form input {
            border: 1px solid #C2CBD1; border-radius: 8px; padding: 4px 5px;
            font-size: 0.92rem; width: auto; box-sizing: border-box; flex: 0 0 190px;
        }

        /* ===== Drukāšana ===== */
        @media print {
            .page-print-noma .page-wrapper > div:first-child,
            .page-print-noma .navigacija,
            .page-print-noma .noma-filter-form,
            .page-print-noma .noma-pagination,
            .page-print-noma footer,
            .page-print-noma .alert,
            .page-print-noma .btn-action,
            .page-print-noma th:last-child,
            .page-print-noma td:last-child { display: none !important; }
            .page-print-noma h2 { margin: 0 0 12px 0; font-size: 20px; }
            .page-print-noma .table {
                width: 100% !important; border: 1px solid #000; border-collapse: collapse; font-size: 12px;
            }
            .page-print-noma .table thead { background: #fff !important; color: #000 !important; }
            .page-print-noma .table thead th,
            .page-print-noma .table tbody td { border: 1px solid #000 !important; padding: 6px !important; }
            .page-print-noma .table tbody tr:hover { background: transparent !important; }
            .page-print-noma .table thead th a.sort-link { color: #000 !important; text-decoration: none; }
        }
    </style>
</head>
<body class="{{ request()->is('Noma*') ? 'page-print-noma' : '' }}">

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

            <a href="/Noma">Nomas</a>
            @if(Auth::user()->isAdmin())
                <a href="/Klienti">Klienti</a>
            @endif
            <a href="/Veidi">Vagonu veidi</a>
            <a href="/Kravas">Krāvu veidi</a>

            <span style="position: absolute; right: 120px; font-size: 13px;">{{ Auth::user()->name }}</span>
            <a href="/logout" style="position: absolute; right: 10px; border-radius:8px;  border: 1px solid #b62100; 
            padding: 5px; background:#b62100; text-decoration: none; color: #ffffff;">Izlogoties</a>

        @else
            <a href="/">Par uzņēmumu</a>
            <a href="/Login" style="position: absolute; right: 10px; border-radius:8px;  border: 1px solid #b62100; 
            padding: 5px; background:#b62100; text-decoration: none; color: #ffffff;">Ielogoties</a>
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
