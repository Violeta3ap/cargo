<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
