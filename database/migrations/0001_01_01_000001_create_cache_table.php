<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija kešatmiņas tabulu izveidei.
 * Šī klase izveido cache un cache_locks tabulas kešatmiņas funkcionalitātei Laravel lietojumprogrammā.
 */
return new class extends Migration
{
    /**
     * Palaist migrāciju.
     * Izveido cache tabulu ar laukiem: key (primārā), value (mediumText), expiration (indeksēta).
     * Izveido cache_locks tabulu ar laukiem: key (primārā), owner, expiration (indeksēta).
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
        });
    }

    /**
     * Atcelt migrāciju.
     * Izdzēš cache un cache_locks tabulas.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
