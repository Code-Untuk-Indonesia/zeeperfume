<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->unique()->constrained('transactions')->cascadeOnDelete();
            $table->string('no_resi', 100)->nullable();
            $table->decimal('biaya_kirim', 15, 2)->default(0);
            $table->string('jenis_pengiriman', 50)->nullable();
            $table->string('nama_penerima', 150);
            $table->string('no_telepon_penerima', 30)->nullable();
            $table->text('alamat_tujuan');
            $table->text('catatan_kurir')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
