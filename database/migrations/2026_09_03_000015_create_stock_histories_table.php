<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('varian_id')->constrained('product_variants')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('transaksi_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->enum('jenis_riwayat', ['masuk', 'keluar', 'rusak', 'penyesuaian', 'penjualan']);
            $table->integer('qty');
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu');
            $table->timestamps();

            $table->index(['cabang_id', 'varian_id', 'waktu']);
            $table->index('transaksi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
