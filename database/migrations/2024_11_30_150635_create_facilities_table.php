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

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('contractor_id')->constrained();
            $table->foreignId('consultant_id')->constrained();
            $table->foreignId('district_id')->constrained();
            $table->foreignId('village_id')->constrained();
            $table->float('length');
            $table->float('width')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->float('real_1')->nullable();
            $table->float('real_2')->nullable();
            $table->float('real_3')->nullable();
            $table->float('real_4')->nullable();
            $table->float('real_5')->nullable();
            $table->float('real_6')->nullable();
            $table->float('real_7')->nullable();
            $table->float('real_8')->nullable();
            $table->string('photo_0')->nullable();
            $table->string('photo_50')->nullable();
            $table->string('photo_100')->nullable();
            $table->string('photo_pho')->nullable();
            $table->text('note')->nullable();
            $table->text('note_pho')->nullable();
            $table->json('team')->nullable();
            $table->string('construct_type');
            $table->string('spending_type');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};