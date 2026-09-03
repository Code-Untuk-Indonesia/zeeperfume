<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_tempo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->unique()->constrained('transactions')->cascadeOnDelete();
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('jumlah_piutang', 15, 2);
            $table->decimal('sisa_piutang', 15, 2);
            $table->enum('status_tempo', ['belum_lunas', 'lunas', 'dibatalkan'])->default('belum_lunas');
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_penagihan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_tempo');
    }
};
