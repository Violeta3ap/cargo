<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // public function register(Request $request)
    // {
    //     // Registration logic here
    //     'name'=> ['required', 'string', 'max:255'],
    //     //'email'=> ['required', 'string', 'email', 'max:255', 'unique:users'],
    //     'password'=> ['required', 'string', 'min:8'],


    // }

 public function login(Request $request)
{
    $credentials = $request->validate([
        'name' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/')->with('success', 'Veiksmīgi pieteicāties sistēmā!');
    }

   
    return back()->withErrors([
        'name' => 'Nepareizs lietotājvārds vai parole.',
    ])->onlyInput('name');
}


    public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255', 'unique:users'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/')->with('success', 'Reģistrācija veiksmīga! Esat pieteicies sistēmā.');
}

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Esat veiksmīgi izrakstījies no sistēmas.');

}
}