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
























/* Slideshow container */
.slideshow-container {
  width: 1920px;
  height: 500px;
  position: relative;
  margin: auto;
}
 
/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}
 
/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}
 
/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}
 
/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}
 
/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}
 
/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}
 
.active, .dot:hover {
  background-color: #717171;
}
 
/* Fading animation */
.fade {
  animation-name: fade;
  animation-duration: 1.5s;
}
 
@keyframes fade {
  from {opacity: .2} 
  to {opacity: 1}
}
 
/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .prev, .next,.text {font-size: 11px}
}
 
 
/* Full-width input fields */
input[type=text], input[type=password] {
	width: 100%;
	padding: 12px 20px;
	margin: 8px 0;
	display: inline-block;
	border: 1px solid #ccc;
	box-sizing: border-box;
  }
  /* Set a style for all buttons */
  button.login {
	 background-color: #59c1cf;
	color: white;
	padding: 14px 20px;
	margin: 8px 0;
	border: none;
	cursor: pointer;
	width: 100%;
  }
  button:hover {
	opacity: 0.8;
  }
  /* Extra styles for the cancel button */
  .cancelbtn {
	width: auto;
	padding: 10px 18px;
	 background-color: #59c1cf;
  }
  /* Center the image and position the close button */
  .imgcontainer {
	text-align: center;
	margin: 24px 0 12px 0;
	position: relative;
  }
  img.avatar {
	width: 40%;
	border-radius: 50%;
  }
  .container {
	padding: 16px;
  }
  span.psw {
	float: right;
	padding-top: 16px;
  }
  /* The Modal (background) */
  .modal {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	padding-top: 60px;
  }
  /* Modal Content/Box */
  .modal-content {
	background-color: #fefefe;
	margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
	border: 1px solid #888;
	width: 80%; /* Could be more or less, depending on screen size */
  }
  /* The Close Button (x) */
  .close {
	position: absolute;
	right: 25px;
	top: 0;
	color: #000;
	font-size: 35px;
	font-weight: bold;
  }
  .close:hover,
  .close:focus {
	color: red;
	cursor: pointer;
  }
  /* Add Zoom Animation */
  .animate {
	-webkit-animation: animatezoom 0.6s;
	animation: animatezoom 0.6s
  }
  @-webkit-keyframes animatezoom {
	from {-webkit-transform: scale(0)} 
	to {-webkit-transform: scale(1)}
  }
  @keyframes animatezoom {
	from {transform: scale(0)} 
	to {transform: scale(1)}
  }
  /* Change styles for span and cancel button on extra small screens */
  @media screen and (max-width: 300px) {
	span.psw {
	   display: block;
	   float: none;
	}
	.cancelbtn {
	   width: 100%;
	}
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
<!-- 
    <a href="/Login" style="position: absolute; right: 10px; top: 100px; border-radius:8px;  border: 1px solid #59c1cf; 
        padding: 5px; background: linear-gradient(to right, #59c1cf, #ffffff); text-decoration: none; color: #000000;">Ielogoties</a> -->


<li class="login" style="position: absolute; list-style: none; right: 10px; top: 100px; border-radius:8px;  border: 1px solid #59c1cf; 
        padding: 5px; background: linear-gradient(to right, #59c1cf, #ffffff); text-decoration: none; color: #000000;"
         onclick="document.getElementById('id01').style.display='block'; "><a>Ielogoties<a></li>


         
        <!-- <a href="/register" style="position: absolute; right: 110px; top: 100px; border-radius:8px;  border: 1px solid #59c1cf; 
        padding: 5px; background: linear-gradient(to right, #59c1cf, #ffffff); text-decoration: none; color: #000000;">Reģistrēties</a> -->
    @endif

<!-- <div class="sidemenu">
@if(Auth::check())
<a href="/VagonaRaksturojums">Vagonu raksturojums</a>
<a href="/Veidi">Vagonu veidi</a>
<a href="/Kravas">Kravas</a>
<a href="/Amati">Amati</a>
@endif
</div> -->
    

                <div id="id01" class="modal">
              <form class="modal-content animate" action="index.html" method="post">
                <div class="imgcontainer">
                  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
                  <img style="width: 400px; height: 400px; object-fit: cover;">
                </div>

                <div class="container">
                  <label for="name"><b>Username</b></label>
                  <input type="text" placeholder="Enter Username" name="name" required>

                  <label for="password"><b>Password</b></label>
                  <input type="password" placeholder="Enter Password" name="password" required>

                  <button class="login" type="submit" onclick="promptForPassword()">Login</button>
                </div>

                <div class="container" style="background-color:#f1f1f1">
                  <button class="login" type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                </div>
              </form>
            </div>






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