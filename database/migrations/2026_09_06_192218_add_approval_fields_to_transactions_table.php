<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Status pengajuan: none, pending_edit, pending_delete, approved_edit
            $table->enum('approval_status', ['none', 'pending_edit', 'pending_delete', 'approved_edit'])->default('none')->after('cabang_id');
            $table->text('approval_reason')->nullable()->after('approval_status');
            $table->foreignId('approval_by')->nullable()->constrained('users')->nullOnDelete()->after('approval_reason');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['approval_by']);
            $table->dropColumn(['approval_status', 'approval_reason', 'approval_by']);
        });
    }
};