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

        Schema::create('consol_spvs', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->foreignId('procurement_officer_id')->constrained();
            $table->float('bid_value');
            $table->float('correction_value');
            $table->float('nego_value');
            $table->foreignId('consultant_id')->constrained();
            $table->date('invite_date');
            $table->date('evaluation_date');
            $table->date('nego_date');
            $table->date('BAHPL_date');
            $table->date('sppbj_date');
            $table->date('spk_date');
            $table->string('account_type');
            $table->string('program');
            $table->integer('duration');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consol_spvs');
    }
};
