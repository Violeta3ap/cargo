<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija vagonunoma arhīva tabulas izveidei.
 * Šī klase izveido vagonunoma_arhivs tabulu arhīva datiem par vagonu nomām.
 */
return new class extends Migration {
    /**
     * Palaist migrāciju.
     * Ja vagonunoma_arhivs tabula jau eksistē, iziet.
     * Izveido vagonunoma_arhivs tabulu ar laukiem: NomasID, KlientaID (nullable), KravasID (nullable), VeidaID (nullable), VagonuSkaits (nullable), NomasSakumaPeriods (date, nullable), NomasBeiguPeriods (date, nullable), StatusaID (nullable), KopejaMaksa (float, nullable), MaksasID (nullable).
     * Pievieno indeksus NomasID un KlientaID.
     */
    public function up(): void
    {
        if (Schema::hasTable('vagonunoma_arhivs')) {
            return;
        }

        Schema::create('vagonunoma_arhivs', function (Blueprint $table) {
            $table->integer('NomasID');
            $table->integer('KlientaID')->nullable();
            $table->integer('KravasID')->nullable();
            $table->integer('VeidaID')->nullable();
            $table->integer('VagonuSkaits')->nullable();
            $table->date('NomasSakumaPeriods')->nullable();
            $table->date('NomasBeiguPeriods')->nullable();
            $table->integer('StatusaID')->nullable();
            $table->float('KopejaMaksa')->nullable();
            $table->integer('MaksasID')->nullable();

            $table->index('NomasID');
            $table->index('KlientaID');
        });
    }

    /**
     * Atcelt migrāciju.
     * Izdzēš vagonunoma_arhivs tabulu.
     */
    public function down(): void
    {
        Schema::dropIfExists('vagonunoma_arhivs');
    }
};
