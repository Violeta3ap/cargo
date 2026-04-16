<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija darbu rindas tabulu izveidei.
 * Šī klase izveido jobs, job_batches un failed_jobs tabulas darbu apstrādei un kļūdu reģistrēšanai Laravel lietojumprogrammā.
 */
return new class extends Migration
{
    /**
     * Palaist migrāciju.
     * Izveido jobs tabulu ar laukiem: id, queue (indeksēta), payload (longText), attempts (unsignedTinyInteger), reserved_at (unsignedInteger, nullable), available_at (unsignedInteger), created_at (unsignedInteger).
     * Izveido job_batches tabulu ar laukiem: id (primārā), name, total_jobs, pending_jobs, failed_jobs, failed_job_ids (longText), options (mediumText, nullable), cancelled_at (nullable), created_at, finished_at (nullable).
     * Izveido failed_jobs tabulu ar laukiem: id, uuid (unikāls), connection (text), queue (text), payload (longText), exception (longText), failed_at (timestamp ar current).
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Atcelt migrāciju.
     * Izdzēš jobs, job_batches un failed_jobs tabulas.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
