<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija sesiju tabulu izveidei.
 * Šī klase izveido sessions tabulu sesiju funkcionalitātei Laravel lietojumprogrammā.
 */
return new class extends Migration
{
    /**
     * Palaist migrāciju.
     * Izveido sessions tabulu ar laukiem: id (primārā), user_id (ārējā, indeksēta), ip_address, user_agent, payload, last_activity (indeksēta).
     */
    public function up(): void
    {
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
     * Izdzēš sessions tabulu.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};