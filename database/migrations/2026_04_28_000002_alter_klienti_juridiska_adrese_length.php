<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija klienta JuridiskaAdrese kolonnas garuma palielināšanai
 * Palielina JuridiskaAdrese kolonnas maksimālo garumu no 30 uz 255 simboliem
 * Nodrošina saderību ar Laravel validācijas noteikumiem
 */
return new class extends Migration {
    /**
     * Palaist migrāciju.
     * Palielina JuridiskaAdrese kolonnas garumu uz 255 simboliem
     */
    public function up(): void
    {
        Schema::table('klienti', function (Blueprint $table) {
            $table->string('JuridiskaAdrese', 255)->change();
        });
    }

    /**
     * Atcelt migrāciju.
     * Samazina JuridiskaAdrese kolonnas garumu atpakaļ uz 30 simboliem
     */
    public function down(): void
    {
        Schema::table('klienti', function (Blueprint $table) {
            $table->string('JuridiskaAdrese', 30)->change();
        });
    }
};