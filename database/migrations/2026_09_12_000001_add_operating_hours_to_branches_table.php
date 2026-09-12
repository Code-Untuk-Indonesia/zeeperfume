<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table): void {
            if (! Schema::hasColumn('branches', 'jam_buka')) {
                $table->time('jam_buka')->nullable();
            }

            if (! Schema::hasColumn('branches', 'jam_tutup')) {
                $table->time('jam_tutup')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table): void {
            $columnsToDrop = array_values(array_filter(
                ['jam_buka', 'jam_tutup'],
                fn (string $column): bool => Schema::hasColumn('branches', $column),
            ));

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
