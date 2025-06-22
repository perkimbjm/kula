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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();

            // Relasi ke Work
            $table->foreignId('work_id')->constrained('works')->cascadeOnDelete();

            // Track Progress Fisik - Checkboxes
            $table->boolean('survei')->default(false);
            $table->boolean('pemilihan')->default(false);
            $table->boolean('kontrak')->default(false);
            $table->boolean('uang_muka')->default(false);
            $table->boolean('kritis')->default(false);
            $table->boolean('selesai')->default(false);
            $table->boolean('pho')->default(false);
            $table->boolean('aset')->default(false);
            $table->boolean('ppk_dinas')->default(false);
            $table->boolean('bendahara')->default(false);
            $table->boolean('pengguna_anggaran')->default(false);
            $table->boolean('keuangan')->default(false);
            $table->boolean('bank')->default(false);
            $table->boolean('laporan')->default(false);

            // Pemeriksa - multi pilihan dari officers
            $table->json('pemeriksa')->nullable();

            // Koordinat dan dimensi
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->decimal('panjang', 8, 2)->nullable();
            $table->decimal('lebar', 8, 2)->nullable();

            // Upload files
            $table->json('foto_survey')->nullable();
            $table->json('foto_pho')->nullable();
            $table->json('lampiran')->nullable();

            // Status dan catatan
            $table->string('status')->default('baik');
            $table->text('catatan_tim_teknis')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
