<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * LoginController - Pārvalda ielogošanos, reģistrāciju un izlogošanos
 * 
 * Šis kontrolieris nodrošina sistēmas autentifikācijas funkcionalitāti:
 * - Lietotāju ielogošanu (login)
 * - Jaunus lietotāju reģistrāciju
 * - Lietotāju izlogošanu (logout)
 */
class LoginController extends Controller
{
    /**
     * Pieslēdz lietotāju sistēmā
     * 
     * Lietotājs iesniedz lietotājvārdu (name) un paroli (password)
     * Ja kredenciali ir pareizi, tiek izveidota autentificēta sesija
     * 
     * @param Illuminate\Http\Request $request - Pieprasījums ar login datiem
     * @return Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validē obligātos laukus ielogošanai
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Mēģina autentificēt lietotāju ar sniegrajiem kredenciāliem
        if (Auth::attempt($credentials)) {
            // Ja autentifikācija veiksmīga, regenerē sesijas ID (drošības nolūkos)
            $request->session()->regenerate();
            // Novirza uz sākumlapu ar apstiprinājuma ziņojumu
            return redirect()->intended('/')->with('success', 'Veiksmīgi pieteicāties sistēmā!');
        }

        // Ja autentifikācija neveiksmīga, atgriež formu ar kļūdas ziņojumu
        return back()->withErrors([
            'name' => 'Nepareizs lietotājvārds vai parole.',
        ])->onlyInput('name');
    }

    /**
     * Reģistrē jaunu lietotāju sistēmā
     * 
     * Pārbauda:
     * - Lietotājvārds ir unikāls (nav duplicetei)
     * - E-pasts ir validš un unikāls
     * - Parole ir vismaz 8 simboli gara un ir apstiprināta (confirmed)
     * 
     * @param Illuminate\Http\Request $request - Pieprasījums ar reģistrācijas datiem
     * @return Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validē reģistrācijas datus
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Izveido jaunu lietotāju ar droši hashotu paroli
        // Hash::make() izmanto bcrypt algoritmu paroles šifrēšanai
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Automātiski pielogina jauno lietotāju
        Auth::login($user);

        // Novirza uz sākumlapu ar apstiprinājuma ziņojumu
        return redirect('/')->with('success', 'Reģistrācija veiksmīga! Esat pieteicies sistēmā.');
    }

    /**
     * Izraksta lietotāju no sistēmas
     * 
     * Veic:
     * - Nologojas lietotāju no Auth sesijas
     * - Invalida pašreizējo sesiju
     * - Regenerē sesijas CSRF tokenu (drošības nolūkos)
     * 
     * @param Illuminate\Http\Request $request - Pieprasījums
     * @return Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Nologojas lietotāju
        Auth::logout();
        
        // Invalida pašreizējo sesiju (beidz sesiju)
        $request->session()->invalidate();
        
        // Regenerē CSRF tokenu (novērš session fixation uzbrukumus)
        $request->session()->regenerateToken();

        // Novirza uz sākumlapu ar apstiprinājuma ziņojumu
        return redirect('/')->with('success', 'Esat veiksmīgi izrakstījies no sistēmas.');
    }
}