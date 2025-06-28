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
        Schema::disableForeignKeyConstraints();
        Schema::table('consol_spv_details', function (Blueprint $table) {
            // Langsung drop kolom yang tidak diperlukan
            if (Schema::hasColumn('consol_spv_details', 'consolidation_id')) {
                $table->dropColumn('consolidation_id');
            }
            if (Schema::hasColumn('consol_spv_details', 'budget')) {
                $table->dropColumn('budget');
            }
            if (Schema::hasColumn('consol_spv_details', 'consol_spv_id')) {
                $table->dropColumn('consol_spv_id');
            }
            // Tambah kolom ee jika belum ada
            if (!Schema::hasColumn('consol_spv_details', 'ee')) {
                $table->decimal('ee', 10, 2)->default(0.00)->after('name');
            }
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('consol_spv_details', function (Blueprint $table) {
            $table->float('budget')->nullable();
            $table->unsignedBigInteger('consol_spv_id')->nullable();
            $table->unsignedBigInteger('consolidation_id')->nullable();
            $table->dropColumn('ee');
        });
        Schema::enableForeignKeyConstraints();
    }
};
