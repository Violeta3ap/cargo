<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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

    public function down(): void
    {
        // Intentionally left empty to avoid forcing a stricter schema again.
    }
};
