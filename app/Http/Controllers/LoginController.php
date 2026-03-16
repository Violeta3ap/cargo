<?php

namespace App\Http\Controllers; // Norāda controller atrašanās vietu projektā

use Illuminate\Http\Request; // Klase formu datu saņemšanai
use Illuminate\Support\Facades\Auth; // Laravel autentifikācijas funkcijas
use App\Models\User; // User modelis darbam ar lietotāju tabulu
use Illuminate\Support\Facades\Hash; // Paroles šifrēšanai
use App\Http\Controllers\Controller; // Pamata controller klase

class LoginController extends Controller // Izveido Login controller klasi
{

 public function login(Request $request) // Lietotāja pieslēgšanās funkcija
{
    $credentials = $request->validate([ // Pārbauda ievadītos datus
        'name' => ['required'], // Lietotājvārds obligāts
        'password' => ['required'], // Parole obligāta
    ]);

    if (Auth::attempt($credentials)) { // Pārbauda vai dati ir pareizi
        $request->session()->regenerate(); // Izveido jaunu sesiju drošībai
        return redirect()->intended('/')->with('success', 'Veiksmīgi pieteicāties sistēmā!');
        // Ja viss pareizi, pāradresē uz galveno lapu
    }

    return back()->withErrors([ // Ja dati nepareizi
        'name' => 'Nepareizs lietotājvārds vai parole.', // Parāda kļūdas ziņu
    ])->onlyInput('name'); // Saglabā ievadīto lietotājvārdu
}


public function register(Request $request) // Lietotāja reģistrācijas funkcija
{
    $validated = $request->validate([ // Pārbauda ievadītos reģistrācijas datus
        'name' => ['required', 'string', 'max:255', 'unique:users'], // Lietotājvārds obligāts un unikāls
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Epasts obligāts un unikāls
        'password' => ['required', 'string', 'min:8', 'confirmed'], // Parole vismaz 8 simboli un apstiprināta
    ]);

    $user = User::create([ // Izveido jaunu lietotāju datubāzē
        'name' => $validated['name'], // Saglabā lietotājvārdu
        'email' => $validated['email'], // Saglabā epastu
        'password' => Hash::make($validated['password']), // Šifrē un saglabā paroli
    ]);

    Auth::login($user); // Automātiski pieslēdz jauno lietotāju

    return redirect('/')->with('success', 'Reģistrācija veiksmīga! Esat pieteicies sistēmā.');
    // Pāradresē uz galveno lapu
}

public function logout(Request $request) // Lietotāja izrakstīšanās funkcija
{
    Auth::logout(); // Izraksta lietotāju no sistēmas

    $request->session()->invalidate(); // Dzēš veco sesiju
    $request->session()->regenerateToken(); // Izveido jaunu drošības tokenu

    return redirect('/')->with('success', 'Esat veiksmīgi izrakstījies no sistēmas.');
    // Pāradresē uz galveno lapu
}
}