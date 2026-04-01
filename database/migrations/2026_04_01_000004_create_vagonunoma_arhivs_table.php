<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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

    public function down(): void
    {
        Schema::dropIfExists('vagonunoma_arhivs');
    }
};
