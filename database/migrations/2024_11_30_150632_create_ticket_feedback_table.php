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

        Schema::create('ticket_feedback', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('ticket_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->text('feedback');
            $table->integer('rating');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_feedback');
    }
};
