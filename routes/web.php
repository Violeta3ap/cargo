<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DarbiniekuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NomaController;
use App\Http\Controllers\NoslogojumsController;


Route::post('/Login/submit', [LoginController::class, 'login'])->name('login.submit');

route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::get('/Login', function () {
    return view('Login');
})->name('login');







Route::get('/', function () {
    return view('home');
});


Route::middleware(['auth', 'nocache'])->group(function () {


// API routes nomas aprēķiniem
Route::post('/api/noma/check-availability', [NomaController::class, 'checkAvailability']);
Route::get('/api/krava/{id}/veids', [NomaController::class, 'getVeidsByKrava']);
Route::post('/api/noma/calculate', [NomaController::class, 'calculateTotal']);
Route::get('/Noma/recalculate', [NomaController::class, 'recalculateAll']);



// Vagonu noma routes
Route::get('/Noma', 'App\Http\Controllers\NomaController@showAllNoma');
Route::get('/Noma/jauns', 'App\Http\Controllers\NomaController@create');
Route::post('/Noma/jaunsSubmit', 'App\Http\Controllers\NomaController@NomaSubmit');
Route::get('/Noma/{id}/delete', 'App\Http\Controllers\NomaController@delete');
Route::get('/Noma/arhivs', 'App\Http\Controllers\NomaController@showArchive');
Route::get('/Noma/arhivs/{id}/restore', 'App\Http\Controllers\NomaController@restoreFromArchive');
Route::get('/Noma/{id}/details', 'App\Http\Controllers\NomaController@details');
Route::get('/Noma/{id}/edit', 'App\Http\Controllers\NomaController@edit');
Route::post('/Noma/{id}/editSubmit', 'App\Http\Controllers\NomaController@editSubmit');


// // Darbinieku routes
Route::get('/Darbinieki', 'App\Http\Controllers\DarbiniekuController@showAllDarbinieki');
Route::get('/Darbinieki/jauns', 'App\Http\Controllers\DarbiniekuController@create');
Route::post('/Darbinieki/jaunsSubmit', 'App\Http\Controllers\DarbiniekuController@DarbiniekiSubmit');
Route::get('/Darbinieki/{id}/delete', 'App\Http\Controllers\DarbiniekuController@delete');
Route::get('/Darbinieki/{id}/details', 'App\Http\Controllers\DarbiniekuController@details');
Route::get('/Darbinieki/{id}/edit', 'App\Http\Controllers\DarbiniekuController@edit');
Route::post('/Darbinieki/{id}/editSubmit', 'App\Http\Controllers\DarbiniekuController@editSubmit');


// // Amata datu routes
Route::get('/Amati', 'App\Http\Controllers\AmataController@showAllAmati');
Route::get('/Amati/jauns', 'App\Http\Controllers\AmataController@create');
Route::post('/Amati/jaunsSubmit', 'App\Http\Controllers\AmataController@DatuSubmit');
Route::get('/Amati/{id}/delete', 'App\Http\Controllers\AmataController@delete');
Route::get('/Amati/{id}/details', 'App\Http\Controllers\AmataController@details');
Route::get('/Amati/{id}/edit', 'App\Http\Controllers\AmataController@edit');
Route::post('/Amati/{id}/editSubmit', 'App\Http\Controllers\AmataController@editSubmit');


// // Kravas datu routes
Route::get('/Kravas', 'App\Http\Controllers\KravasController@showAllKrava');
Route::get('/Kravas/jauns', 'App\Http\Controllers\KravasController@create');
Route::post('/Kravas/jaunsSubmit', 'App\Http\Controllers\KravasController@DatuSubmit');
Route::get('/Kravas/{id}/delete', 'App\Http\Controllers\KravasController@delete');
Route::get('/Kravas/{id}/details', 'App\Http\Controllers\KravasController@details');
Route::get('/Kravas/{id}/edit', 'App\Http\Controllers\KravasController@edit');
Route::post('/Kravas/{id}/editSubmit', 'App\Http\Controllers\KravasController@editSubmit');


// // Veidu  datu routes
Route::get('/Veidi', 'App\Http\Controllers\VeidiController@showAllVeidi');
Route::get('/Veidi/jauns', 'App\Http\Controllers\VeidiController@create');
Route::post('/Veidi/jaunsSubmit', 'App\Http\Controllers\VeidiController@DatuSubmit');
Route::get('/Veidi/{id}/delete', 'App\Http\Controllers\VeidiController@delete');
Route::get('/Veidi/{id}/details', 'App\Http\Controllers\VeidiController@details');
Route::get('/Veidi/{id}/edit', 'App\Http\Controllers\VeidiController@edit');
Route::post('/Veidi/{id}/editSubmit', 'App\Http\Controllers\VeidiController@editSubmit');

// // Klienta datu routes
Route::get('/Klienti', 'App\Http\Controllers\KlientiController@showAllKlienti');
Route::get('/Klienti/jauns', 'App\Http\Controllers\KlientiController@create');
Route::post('/Klienti/jaunsSubmit', 'App\Http\Controllers\KlientiController@KlientiSubmit');
Route::get('/Klienti/{id}/delete', 'App\Http\Controllers\KlientiController@delete');
Route::get('/Klienti/{id}/details', 'App\Http\Controllers\KlientiController@details');
Route::get('/Klienti/{id}/edit', 'App\Http\Controllers\KlientiController@edit');
Route::post('/Klienti/{id}/editSubmit', 'App\Http\Controllers\KlientiController@editSubmit');

// Noslogojums routes
Route::get('/Noslogojums', [NoslogojumsController::class, 'show']);
Route::get('/Noslogojums/periods', [NoslogojumsController::class, 'showPeriod']);

});




