<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija atteikuma iemesla pievienošanai nomas tabulām.
 * Šī klase pievieno AtteikumaIemesls kolonnu vagonunoma un vagonunoma_arhivs tabulām.
 */
return new class extends Migration {
    /**
     * Palaist migrāciju.
     * Ja vagonunoma tabula eksistē un nav AtteikumaIemesls kolonnas, pievieno text nullable kolonnu pēc StatusaID.
     * Ja vagonunoma_arhivs tabula eksistē un nav AtteikumaIemesls kolonnas, pievieno text nullable kolonnu pēc StatusaID.
     */
    public function up(): void
    {
        if (Schema::hasTable('vagonunoma') && !Schema::hasColumn('vagonunoma', 'AtteikumaIemesls')) {
            Schema::table('vagonunoma', function (Blueprint $table) {
                $table->text('AtteikumaIemesls')->nullable()->after('StatusaID');
            });
        }

        if (Schema::hasTable('vagonunoma_arhivs') && !Schema::hasColumn('vagonunoma_arhivs', 'AtteikumaIemesls')) {
            Schema::table('vagonunoma_arhivs', function (Blueprint $table) {
                $table->text('AtteikumaIemesls')->nullable()->after('StatusaID');
            });
        }
    }

    /**
     * Atcelt migrāciju.
     * Ja vagonunoma_arhivs tabula eksistē un ir AtteikumaIemesls kolonna, izdzēš to.
     * Ja vagonunoma tabula eksistē un ir AtteikumaIemesls kolonna, izdzēš to.
     */
    public function down(): void
    {
        if (Schema::hasTable('vagonunoma_arhivs') && Schema::hasColumn('vagonunoma_arhivs', 'AtteikumaIemesls')) {
            Schema::table('vagonunoma_arhivs', function (Blueprint $table) {
                $table->dropColumn('AtteikumaIemesls');
            });
        }

        if (Schema::hasTable('vagonunoma') && Schema::hasColumn('vagonunoma', 'AtteikumaIemesls')) {
            Schema::table('vagonunoma', function (Blueprint $table) {
                $table->dropColumn('AtteikumaIemesls');
            });
        }
    }
};
