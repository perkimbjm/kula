<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('works', function (Blueprint $table) {
        $table->id();
        $table->integer('year');
        $table->string('name');
        $table->date('contract_date')->nullable();
        $table->string('contract_number');
        $table->foreignId('contractor_id')->constrained();
        $table->foreignId('consultant_id')->constrained();
        $table->foreignId('supervisor_id')->constrained('consultants');
        $table->float('contract_value');
        $table->float('progress')->default(0);
        $table->date('cutoff');
        $table->string('status')->default('belum kontrak');
        $table->float('paid')->nullable();
        $table->timestamps();
        });

         Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};