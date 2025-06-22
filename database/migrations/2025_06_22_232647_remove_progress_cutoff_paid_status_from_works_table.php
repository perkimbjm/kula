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
        Schema::table('works', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn(['progress', 'cutoff', 'paid', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            // Recreate columns
            $table->float('progress')->default(0)->after('contract_value');
            $table->date('cutoff')->after('progress');
            $table->string('status')->default('belum kontrak')->after('cutoff');
            $table->float('paid')->nullable()->after('status');
        });
    }
};
