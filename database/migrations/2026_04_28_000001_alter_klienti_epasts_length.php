<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija klienta Epasts kolonnas garuma palielināšanai
 * Palielina Epasts kolonnas maksimālo garumu no 25 uz 255 simboliem
 * Nodrošina saderību ar Laravel validācijas noteikumiem
 */
return new class extends Migration {
    /**
     * Palaist migrāciju.
     * Palielina Epasts kolonnas garumu uz 255 simboliem
     */
    public function up(): void
    {
        Schema::table('klienti', function (Blueprint $table) {
            $table->string('Epasts', 255)->change();
        });
    }

    /**
     * Atcelt migrāciju.
     * Samazina Epasts kolonnas garumu atpakaļ uz 25 simboliem
     */
    public function down(): void
    {
        Schema::table('klienti', function (Blueprint $table) {
            $table->string('Epasts', 25)->change();
        });
    }
};