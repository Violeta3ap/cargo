<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migrācija AtteikumaIemesls kolonnas padarīšanai nullable.
 * Šī klase nodrošina, ka AtteikumaIemesls kolonna ir TEXT NULL vagonunoma un vagonunoma_arhivs tabulās MySQL datubāzē.
 */
return new class extends Migration {
    /**
     * Palaist migrāciju.
     * Ja datubāzes draiveris nav MySQL, iziet.
     * Katrai tabulai vagonunoma un vagonunoma_arhivs, ja tabula eksistē un ir AtteikumaIemesls kolonna, izpilda ALTER TABLE, lai padarītu to TEXT NULL.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (['vagonunoma', 'vagonunoma_arhivs'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'AtteikumaIemesls')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `AtteikumaIemesls` TEXT NULL");
            }
        }
    }

    /**
     * Atcelt migrāciju.
     * Tīši atstāts tukšs, lai izvairītos no stingrākas shēmas piespiešanas atkārtoti.
     */
    public function down(): void
    {
        // Intentionally left empty to avoid forcing a stricter schema again.
    }
};
