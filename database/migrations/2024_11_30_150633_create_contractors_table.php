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

        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('akta');
            $table->date('founding_date');
            $table->string('notary');
            $table->string('address');
            $table->string('npwp');
            $table->string('leader');
            $table->string('position');
            $table->string('slug');
            $table->string('account_number');
            $table->string('account_holder');
            $table->string('header_scan')->nullable();
            $table->string('account_scan')->nullable();
            $table->string('npwp_scan')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};
