<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Menggunakan decimal agar bisa menyimpan nilai koma seperti 0.5
            $table->decimal('nilai_poin', 8, 2)->default(0)->after('nama_kategori');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('nilai_poin');
        });
    }
};
