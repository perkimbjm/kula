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

        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('supervisor');
            $table->foreignId('district_id')->constrained();
            $table->foreignId('village_id')->constrained();
            $table->float('length');
            $table->string('type');
            $table->string('program');
            $table->string('lat');
            $table->string('lng');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
