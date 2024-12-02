<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('information', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
        });
    }
};