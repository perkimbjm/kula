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
        Schema::table('consol_plan_details', function (Blueprint $table) {
            $table->dropColumn(['budget', 'consol_plan_id', 'consolidation_id']);
            $table->decimal('ee', 10, 2)->default(0.00)->after('name');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('consol_plan_details', function (Blueprint $table) {
            $table->float('budget')->nullable();
            $table->unsignedBigInteger('consol_plan_id')->nullable();
            $table->unsignedBigInteger('consolidation_id')->nullable();
            $table->dropColumn('ee');
        });
        Schema::enableForeignKeyConstraints();
    }
};
