<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->integer('work_id')->nullable();
            $table->text('photo_0_url')->nullable()->after('photo_0');
            $table->text('photo_50_url')->nullable()->after('photo_50');
            $table->text('photo_100_url')->nullable()->after('photo_100');
            $table->text('photo_pho_url')->nullable()->after('photo_pho');
            $table->text('asbuilt_drawing')->nullable();
            $table->text('file_konsultan_pengawas')->nullable();
            $table->text('file_kontraktor_pelaksana')->nullable();
            $table->text('file_shp')->nullable();
            $table->text('file_konsultan_perencana')->nullable();
            $table->json('laporan')->nullable();
            $table->string('progress_status')->nullable();
            $table->string('rab')->nullable();
            $table->string('rt')->nullable();
            $table->string('shop_drawing')->nullable();
            $table->string('phone')->nullable();
            $table->text('shop_drawing_url')->nullable();
            $table->text('asbuilt_drawing_url')->nullable();
            $table->text('rab_url')->nullable();
            $table->text('laporan_url')->nullable();
            $table->text('file_shp_url')->nullable();
            $table->text('file_konsultan_perencana_url')->nullable();
            $table->text('file_konsultan_pengawas_url')->nullable();
            $table->text('file_kontraktor_pelaksana_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn([
                'work_id',
                'asbuilt_drawing',
                'file_konsultan_pengawas',
                'file_kontraktor_pelaksana',
                'file_shp',
                'file_konsultan_perencana',
                'laporan',
                'progress_status',
                'rab',
                'rt',
                'shop_drawing',
                'phone',
                'photo_0_url',
                'photo_50_url',
                'photo_100_url',
                'photo_pho_url',
                'shop_drawing_url',
                'asbuilt_drawing_url',
                'rab_url',
                'laporan_url',
                'file_shp_url',
                'file_konsultan_perencana_url',
                'file_konsultan_pengawas_url',
                'file_kontraktor_pelaksana_url',
            ]);
        });
    }
};