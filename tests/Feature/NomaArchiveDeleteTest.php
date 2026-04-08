<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate');

    Schema::table('users', function (Blueprint $table) {
        $table->integer('AmataID')->nullable();
    });

    Schema::create('amati', function (Blueprint $table) {
        $table->integer('AmataID')->primary();
        $table->string('Nosaukums', 50);
    });

    Schema::create('klienti', function (Blueprint $table) {
        $table->integer('KlientaID')->primary();
        $table->string('Vards')->nullable();
        $table->string('Uzvards')->nullable();
        $table->string('UznemumaNosaukums')->nullable();
        $table->string('Epasts')->nullable();
    });

    Schema::create('vagonunoma', function (Blueprint $table) {
        $table->integer('NomasID')->primary();
        $table->integer('KlientaID')->nullable();
        $table->integer('KravasID')->nullable();
        $table->integer('VeidaID')->nullable();
        $table->integer('VagonuSkaits')->nullable();
        $table->date('NomasSakumaPeriods')->nullable();
        $table->date('NomasBeiguPeriods')->nullable();
        $table->float('KopejaMaksa')->nullable();
        $table->integer('StatusaID')->nullable();
        $table->integer('MaksasID')->nullable();
        $table->text('AtteikumaIemesls')->nullable();
    });

    Schema::create('noslogojums', function (Blueprint $table) {
        $table->integer('NomasID')->primary();
        $table->date('NomasSakumaPeriods')->nullable();
        $table->date('NomasBeiguPeriods')->nullable();
        $table->integer('VeidaID')->nullable();
    });
});

it('allows deleting a completed rental by moving it to the archive', function () {
    DB::table('amati')->insert([
        'AmataID' => 1,
        'Nosaukums' => 'Admins',
    ]);

    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => 'password',
        'AmataID' => 1,
    ]);

    DB::table('vagonunoma')->insert([
        'NomasID' => 123,
        'KlientaID' => 1,
        'KravasID' => 1,
        'VeidaID' => 1,
        'VagonuSkaits' => 4,
        'NomasSakumaPeriods' => now()->subDays(5)->toDateString(),
        'NomasBeiguPeriods' => now()->subDay()->toDateString(),
        'KopejaMaksa' => 100.50,
        'StatusaID' => 2,
        'MaksasID' => 1,
        'AtteikumaIemesls' => null,
    ]);

    DB::table('noslogojums')->insert([
        'NomasID' => 123,
        'NomasSakumaPeriods' => now()->subDays(5)->toDateString(),
        'NomasBeiguPeriods' => now()->subDay()->toDateString(),
        'VeidaID' => 1,
    ]);

    $response = $this->actingAs($user)->get('/Noma/123/delete');

    $response->assertRedirect('/Noma');
    $response->assertSessionHas('success', 'Ieraksts tika arhivēts.');

    expect(DB::table('vagonunoma')->where('NomasID', 123)->exists())->toBeFalse();
    expect(DB::table('vagonunoma_arhivs')->where('NomasID', 123)->exists())->toBeTrue();
    expect(DB::table('noslogojums')->where('NomasID', 123)->exists())->toBeFalse();
});
