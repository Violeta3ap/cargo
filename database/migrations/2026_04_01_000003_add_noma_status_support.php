<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija nomas statusu atbalsta pievienošanai.
 * Šī klase izveido NomasStatuss un MaksasStatuss tabulas, pievieno datus, un paplašina vagonunoma tabulu ar statusa laukiem.
 * Nodrošina statusu pārvaldību nomām un maksājumiem.
 */
return new class extends Migration {
    /**
     * Palaist migrāciju.
     * Pārbauda un izveido NomasStatuss tabulu ar StatusaID (primārā) un Nosaukums.
     * Pārbauda un izveido MaksasStatuss tabulu ar MaksasID (primārā) un Nosaukums.
     * Ievieto datus NomasStatuss: Pieteikts, Pieņemts, Noraidīts.
     * Ievieto datus MaksasStatuss: Apmaksāts, Nav apmaksāts.
     * Ja vagonunoma tabula eksistē, pievieno StatusaID un MaksasID kolonnas pēc KopejaMaksa.
     * Atjaunina esošos ierakstus vagonunoma ar StatusaID = 1 (Pieteikts), ja StatusaID ir null.
     */
    public function up(): void
    {
        if (!Schema::hasTable('NomasStatuss')) {
            Schema::create('NomasStatuss', function (Blueprint $table) {
                $table->integer('StatusaID')->primary();
                $table->string('Nosaukums', 50);
            });
        }

        if (!Schema::hasTable('MaksasStatuss')) {
            Schema::create('MaksasStatuss', function (Blueprint $table) {
                $table->integer('MaksasID')->primary();
                $table->string('Nosaukums', 50);
            });
        }

        DB::table('NomasStatuss')->upsert([
            ['StatusaID' => 1, 'Nosaukums' => 'Pieteikts'],
            ['StatusaID' => 2, 'Nosaukums' => 'Pieņemts'],
            ['StatusaID' => 3, 'Nosaukums' => 'Noraidīts'],
        ], ['StatusaID'], ['Nosaukums']);

        DB::table('MaksasStatuss')->upsert([
            ['MaksasID' => 1, 'Nosaukums' => 'Apmaksāts'],
            ['MaksasID' => 2, 'Nosaukums' => 'Nav apmaksāts'],
        ], ['MaksasID'], ['Nosaukums']);

        if (Schema::hasTable('vagonunoma')) {
            Schema::table('vagonunoma', function (Blueprint $table) {
                if (!Schema::hasColumn('vagonunoma', 'StatusaID')) {
                    $table->integer('StatusaID')->nullable()->after('KopejaMaksa');
                }

                if (!Schema::hasColumn('vagonunoma', 'MaksasID')) {
                    $table->integer('MaksasID')->nullable()->after('StatusaID');
                }
            });

            $pieteiktsId = DB::table('NomasStatuss')->where('Nosaukums', 'Pieteikts')->value('StatusaID');
            if ($pieteiktsId !== null) {
                DB::table('vagonunoma')
                    ->whereNull('StatusaID')
                    ->update(['StatusaID' => (int) $pieteiktsId]);
            }
        }
    }

    /**
     * Atcelt migrāciju.
     * Ja vagonunoma tabula eksistē, izdzēš MaksasID un StatusaID kolonnas.
     * Izdzēš MaksasStatuss un NomasStatuss tabulas.
     */
    public function down(): void
    {
        if (Schema::hasTable('vagonunoma')) {
            Schema::table('vagonunoma', function (Blueprint $table) {
                if (Schema::hasColumn('vagonunoma', 'MaksasID')) {
                    $table->dropColumn('MaksasID');
                }

                if (Schema::hasColumn('vagonunoma', 'StatusaID')) {
                    $table->dropColumn('StatusaID');
                }
            });
        }

        Schema::dropIfExists('MaksasStatuss');
        Schema::dropIfExists('NomasStatuss');
    }
};
