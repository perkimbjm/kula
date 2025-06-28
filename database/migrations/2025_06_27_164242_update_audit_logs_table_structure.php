<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Drop kolom lama yang tidak sesuai
            $table->dropColumn(['message', 'context']);

            // Tambah kolom baru sesuai model App\Models\AuditLog
            $table->string('action')->after('id');
            $table->string('model_type')->after('action');
            $table->string('model_id')->after('model_type');

            // Tambah timestamps
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Kembalikan struktur lama
            $table->dropColumn(['action', 'model_type', 'model_id', 'updated_at']);

            // Tambah kembali kolom lama
            $table->string('message')->after('id');
            $table->longText('context')->after('message');
        });
    }
};
