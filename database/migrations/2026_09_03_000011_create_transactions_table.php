<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasir_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('nomor_nota', 40)->unique();
            $table->timestamp('tanggal_waktu');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->string('deskripsi_diskon')->nullable();
            $table->decimal('total_belanja', 15, 2);
            $table->decimal('nominal_bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->enum('metode_bayar', ['cash', 'qris', 'transfer', 'cash_tempo']);
            $table->foreignId('cabang_id')->constrained('branches')->restrictOnDelete();
            $table->timestamps();

            $table->index(['cabang_id', 'tanggal_waktu']);
            $table->index('metode_bayar');
            $table->index('kasir_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
