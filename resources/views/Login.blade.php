<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Ielogošana</title> 

    
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
    </style>
</head>

<body>

    
    <div class="center-wrap">
        <div class="card custom p-4">
            <h2 class="text-center mb-4">Ielogošana</h2> 

            
            @if ($errors->any())
                <div style="background-color: #ffe8e8; border: 1px solid #ff9999; color: #c00; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li> 
                        @endforeach
                    </ul>
                </div>
            @endif

            
            <form method="POST" action="/Login/submit">
                @csrf 

                
                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Lietotājvārds</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>

                
                <div style="margin-bottom: 25px;">
                    <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Parole</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>

                
                <button type="submit" style="width: 100%; padding: 12px; background: #5985a8; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s;">
                    Pieteikties
                </button>
            </form>

            
            <div style="text-align: center; margin-top: 20px;">
                <a href="/" style="color: #5985a8; text-decoration: none; font-size: 14px;">Atpakaļ uz sākumlapu</a>
            </div>

            
            <p> Admins:  Violeta parole:parole123;</p>
                <p>
                Klients1: Anna  parole:parole123;</p>
                <p>
                Klients2: Roberts  parole:parole123;</p>
            
        </div>
    </div>

</body>
</html>
