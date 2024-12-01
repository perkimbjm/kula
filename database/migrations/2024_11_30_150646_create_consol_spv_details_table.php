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

        Schema::create('consol_spv_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consolidation_id')->constrained('consol_spvs');
            $table->float('budget');
            $table->string('name');
            $table->float('nego_value');
            $table->foreignId('consol_spv_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consol_spv_details');
    }
};
