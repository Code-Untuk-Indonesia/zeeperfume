<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('kategori_pengeluaran', 100);
            $table->string('nama_pengeluaran', 150);
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_pengeluaran');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['cabang_id', 'tanggal_pengeluaran']);
            $table->index('kategori_pengeluaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
