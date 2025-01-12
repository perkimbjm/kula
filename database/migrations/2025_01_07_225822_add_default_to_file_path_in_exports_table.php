<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->string('file_path')->default('')->change(); // Berikan nilai default
        });
    }

    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->string('file_path')->default(null)->change(); // Kembalikan ke nilai default null
        });
    }
};