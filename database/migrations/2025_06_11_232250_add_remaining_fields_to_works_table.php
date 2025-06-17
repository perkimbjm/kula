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
        Schema::table('works', function (Blueprint $table) {
            // Field yang masih diperlukan (district_id sudah ada)
            $table->foreignId('village_id')->nullable()->after('district_id')->constrained()->comment('Desa');
            $table->string('rt')->nullable()->after('village_id')->comment('RT');
            $table->decimal('length', 10, 2)->nullable()->after('rt')->comment('Panjang');
            $table->decimal('width', 10, 2)->nullable()->after('length')->comment('Lebar');
            $table->string('phone')->nullable()->after('width')->comment('Telepon');
            $table->string('construction_type')->nullable()->after('phone')->comment('Konstruksi');

            // Koordinat (Opsional)
            $table->string('coordinate_lat')->nullable()->after('construction_type')->comment('Koordinat Latitude');
            $table->string('coordinate_lng')->nullable()->after('coordinate_lat')->comment('Koordinat Longitude');

            // Administrasi & Program (Opsional)
            $table->string('account_code')->nullable()->after('coordinate_lng')->comment('Kode Rekening');
            $table->string('program')->nullable()->after('account_code')->comment('Program');
            $table->string('source')->nullable()->after('program')->comment('Sumber');
            $table->integer('duration')->nullable()->after('source')->comment('Masa (bulan)');

            // Relasi Tim Teknis dan Pejabat Pengadaan (Opsional)
            $table->json('technical_team')->nullable()->after('duration')->comment('Tim Teknis (array)');
            $table->foreignId('procurement_officer_id')->nullable()->after('technical_team')->constrained()->comment('Pejabat Pengadaan');

            // Nilai & Penawaran (Opsional)
            $table->decimal('hps', 15, 2)->nullable()->after('procurement_officer_id')->comment('Harga Perkiraan Sendiri');
            $table->decimal('bid_value', 15, 2)->nullable()->after('hps')->comment('Nilai Penawaran');
            $table->decimal('correction_value', 15, 2)->nullable()->after('bid_value')->comment('Koreksi Aritmatik');
            $table->decimal('nego_value', 15, 2)->nullable()->after('correction_value')->comment('Harga Nego');

            // Tanggal-Tanggal Proses (Opsional)
            $table->date('invite_date')->nullable()->after('nego_value')->comment('Tanggal Undangan');
            $table->date('evaluation_date')->nullable()->after('invite_date')->comment('Tanggal Evaluasi');
            $table->date('nego_date')->nullable()->after('evaluation_date')->comment('Tanggal Nego');
            $table->date('bahpl_date')->nullable()->after('nego_date')->comment('Tanggal BA-HPL');
            $table->date('sppbj_date')->nullable()->after('bahpl_date')->comment('Tanggal SPPBJ');
            $table->date('spk_date')->nullable()->after('sppbj_date')->comment('Tanggal SPK');

            // Addendum & Penyelesaian (Opsional)
            $table->string('add_number')->nullable()->after('spk_date')->comment('Nomor Addendum');
            $table->date('addendum_date')->nullable()->after('add_number')->comment('Tanggal Addendum');
            $table->decimal('addendum_value', 15, 2)->nullable()->after('addendum_date')->comment('Nilai Addendum');
            $table->string('completion_letter')->nullable()->after('addendum_value')->comment('Surat Keterangan Selesai');
            $table->date('completion_date')->nullable()->after('completion_letter')->comment('Tanggal Surat Keterangan Selesai');
            $table->date('pho_date')->nullable()->after('completion_date')->comment('Tanggal PHO');

            // Jaminan Uang Muka (Opsional)
            $table->string('advance_bap_number')->nullable()->after('pho_date')->comment('No BAP Uang Muka');
            $table->string('advance_guarantee_number')->nullable()->after('advance_bap_number')->comment('No. Jaminan Uang Muka');
            $table->string('advance_guarantor')->nullable()->after('advance_guarantee_number')->comment('Penjamin Uang Muka');
            $table->date('advance_guarantee_date')->nullable()->after('advance_guarantor')->comment('Tanggal Jaminan Uang Muka');
            $table->decimal('advance_value', 15, 2)->nullable()->after('advance_guarantee_date')->comment('Nilai Uang Muka');
            $table->date('advance_payment_date')->nullable()->after('advance_value')->comment('Tanggal Pembayaran Uang Muka');

            // Jaminan Pelunasan (Opsional)
            $table->string('final_bap_number')->nullable()->after('advance_payment_date')->comment('No BAP Pelunasan');
            $table->string('maintenance_guarantee_number')->nullable()->after('final_bap_number')->comment('No. Jaminan Pemeliharaan');
            $table->string('final_guarantor')->nullable()->after('maintenance_guarantee_number')->comment('Penjamin Pelunasan');
            $table->date('final_guarantee_date')->nullable()->after('final_guarantor')->comment('Tanggal Jaminan Pelunasan');
            $table->decimal('final_guarantee_value', 15, 2)->nullable()->after('final_guarantee_date')->comment('Nilai Jaminan');
            $table->date('final_payment_date')->nullable()->after('final_guarantee_value')->comment('Tanggal Pembayaran Pelunasan');

            // Add performance indexes for commonly queried fields
            $table->index(['status', 'year']);
            $table->index(['district_id', 'village_id']);
            $table->index(['contractor_id', 'status']);
            $table->index('updated_at');
            $table->index('year');
            $table->index('account_code');
            $table->index('program');
            $table->index('progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['village_id']);
            $table->dropForeign(['procurement_officer_id']);

            // Drop indexes first
            $table->dropIndex(['status', 'year']);
            $table->dropIndex(['district_id', 'village_id']);
            $table->dropIndex(['contractor_id', 'status']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['year']);
            $table->dropIndex(['account_code']);
            $table->dropIndex(['program']);
            $table->dropIndex(['progress']);

            // Drop columns
            $table->dropColumn([
                'village_id', 'rt', 'length', 'width', 'phone', 'construction_type',
                'coordinate_lat', 'coordinate_lng', 'account_code', 'program', 'source', 'duration',
                'technical_team', 'procurement_officer_id', 'hps', 'bid_value', 'correction_value', 'nego_value',
                'invite_date', 'evaluation_date', 'nego_date', 'bahpl_date', 'sppbj_date', 'spk_date',
                'addendum_date', 'addendum_value', 'completion_letter', 'completion_date', 'pho_date',
                'advance_bap_number', 'advance_guarantee_number', 'advance_guarantor', 'advance_guarantee_date',
                'advance_value', 'advance_payment_date', 'final_bap_number', 'maintenance_guarantee_number',
                'final_guarantor', 'final_guarantee_date', 'final_guarantee_value', 'final_payment_date'
            ]);
        });
    }
};
