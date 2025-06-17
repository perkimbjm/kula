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
        Schema::create('officer_work', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('officer_id');
            $table->unsignedBigInteger('work_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('officer_id')->references('id')->on('officers')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');

            // Performance indexes
            $table->unique(['officer_id', 'work_id']);
            $table->index('officer_id');
            $table->index('work_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_work');
    }
};
