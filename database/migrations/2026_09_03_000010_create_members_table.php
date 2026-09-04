<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('kode_member', 30)->unique();
            $table->string('nama', 150);
            $table->string('no_telp', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->unsignedInteger('poin')->default(0);
            $table->date('tanggal_bergabung');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
