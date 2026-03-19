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
                <a href="/Darbinieki">Darbinieki</a>
                <a href="/Klienti">Klienti</a>
                <a href="/VagonuDati">Vagonu dati</a>
                <a href="/VagonaRaksturojums">Vagonu raksturojums</a>
                <a href="/Veidi">Vagonu veidi</a>
                <a href="/Kravas">Krāvu veidi</a>
                <a href="/Amati">Amati</a>
            @elseif(Auth::user()->isDarbinieks())
                {{-- Darbinieks redz tikai pieļaujamās saites --}}
                <a href="/Noma">Noma</a>
                <a href="/Darbinieki">Darbinieki</a>
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