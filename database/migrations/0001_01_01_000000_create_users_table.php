<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija lietotāju tabulas izveidei.
 * Šī klase izveido lietotāju tabulu ar pamata laukiem, kā arī tabulas paroles atiestatīšanai un sesijām.
 * Nodrošina autentifikācijas pamatu Laravel lietojumprogrammai.
 */
return new class extends Migration
{
    /**
     * Palaist migrāciju.
     * Izveido lietotāju tabulu ar laukiem: id, name, email (unikāls), email_verified_at (neobligāts), password, remember_token, timestamps.
     * Izveido password_reset_tokens tabulu ar email (primārā atslēga), token, created_at.
     * Izveido sessions tabulu ar id (primārā), user_id (ārējā, indeksēta), ip_address, user_agent, payload, last_activity (indeksēta).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Atcelt migrāciju.
     * Izdzēš lietotāju, password_reset_tokens un sessions tabulas.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
