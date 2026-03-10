<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>CARGO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* tumšais fons apkārt */
        }

        img {
        max-width: 100%;
        height: auto;
        }

        .header {
            background-color: rgb(195, 227, 238);
            color: white;
            padding: 10px 20px;
            font-weight: bold;
        }

        .navigacija {
            
            background-color: #59c1cf;
            padding: 8px 10px;
            display: flex;
            gap: 80px;
            align-items: center;
            justify-content: center;
        }

        .navigacija a {
            text-decoration: none;  
        background: linear-gradient(to right, #59c1cf, #ffffff);
            padding: 6px 14px;
            border-radius: 10px;
            color: black;
            font-size: 14px;
            gap: 20px;  
            
        }

        .navigacija a:hover {
            background-color: #59c1cf;
        }

        .content {  
            padding: 150px;/* vidus*/
            background-color: #ffffff;
            min-height: 200px;
        }

    footer {
        background: linear-gradient(90deg, #59c1cf, #ffffff);
        color: #000000;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 20px;
        font-size: 0.95rem;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 100;
        gap: 30px;  
    }





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




        /* Side Menu */
.sidemenu {
    position: fixed;
    left: 0;
    top: 140px;
    width: 200px;
    height: 100%;
    background-color: #59c1cf;
    padding-top: 20px;
}

.sidemenu a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: black;
    background: linear-gradient(to right, #59c1cf, #ffffff);
    margin: 10px;
    border-radius: 10px;
}

.sidemenu a:hover {
    background-color: #ffffff;
}








    </style>
</head>
<body>

<div class="page-wrapper">

        <div style="background: #ffffff; padding: 5px 5px; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);
         font-weight: bold; text-align: center;">
          LDZ CARGO
        </div>

    <div class="navigacija">


    @if(Auth::check())
    <a href="/">Par uzņēmumu</a>
    <a href="/Klienti">Klienti</a>
    <a href="/Noma">Noma</a>
    <a href="/VagonuDati">Nomas papildinājums</a>
    <a href="/Darbinieki">Darbinieki</a>

    @else
    <a href="/">Par uzņēmumu</a>
    @endif

    </div>

    <br> <br>

         @if(Auth::check())
      <a href="/logout" style="position: absolute; right: 10px; top: 100px; border-radius:8px;  border: 1px solid #59c1cf; 
        padding: 5px; background: linear-gradient(to right, #59c1cf, #ffffff); text-decoration: none; color: #000000;">Izlogoties</a>
    @else

    <a href="/Login" style="position: absolute; right: 10px; top: 100px; border-radius:8px;  border: 1px solid #59c1cf; 
        padding: 5px; background: linear-gradient(to right, #59c1cf, #ffffff); text-decoration: none; color: #000000;">Ielogoties</a>


       
    @endif

<!-- <div class="sidemenu">
@if(Auth::check())
<a href="/VagonaRaksturojums">Vagonu raksturojums</a>
<a href="/Veidi">Vagonu veidi</a>
<a href="/Kravas">Kravas</a>
<a href="/Amati">Amati</a>
@endif
</div> -->
    



    <div class="content">
        @yield('content')
    </div>


<footer>
    <div>© 2014–2026 VAS "Latvijas dzelzceļš"</div>
    <div>Emīlijas Benjamiņas iela 3, Rīga, LV-1547</div>
    <div>Uzziņas: 8002 1181</div>
    <div>E-pasts: info@ldz.lv</div>
</footer>



</div>

</body>
</html>