<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Pārvalda ielogošanos, reģistrāciju un izlogošanos.
class LoginController extends Controller
{
    // Pieslēdz lietotāju.
    public function login(Request $request)
    {
        // Validē obligātos laukus ielogošanai.
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Mēģina autentificēt lietotāju.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Veiksmīgi pieteicāties sistēmā!');
        }

        return back()->withErrors([
            'name' => 'Nepareizs lietotājvārds vai parole.',
        ])->onlyInput('name');
    }

    // Reģistrē jaunu lietotāju.
    public function register(Request $request)
    {
        // Validē reģistrācijas datus.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Izveido lietotāju ar droši hashotu paroli.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Reģistrācija veiksmīga! Esat pieteicies sistēmā.');
    }

    // Izraksta lietotāju.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Esat veiksmīgi izrakstījies no sistēmas.');
    }
}