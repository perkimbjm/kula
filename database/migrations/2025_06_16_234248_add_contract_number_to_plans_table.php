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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('contract_number')->after('id')->index();

            // Addendum fields
            $table->string('addendum_number')->nullable()->after('year')->comment('Nomor Addendum');
            $table->date('payment_date')->nullable()->after('addendum_number')->comment('Tanggal Pembayaran');
            $table->decimal('payment_value', 15, 2)->nullable()->after('payment_date')->comment('Nilai Pembayaran');

            // BA LKPP
            $table->string('ba_lkpp')->nullable()->after('payment_value')->comment('BA LKPP');

            // Indexes untuk performance
            $table->index('year');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Drop indexes first
            $table->dropColumn('contract_number');
            $table->dropIndex(['year']);
            $table->dropIndex(['payment_date']);

            // Drop columns
            $table->dropColumn([
                'addendum_number',
                'payment_date',
                'payment_value',
                'ba_lkpp'
            ]);
        });
    }
};
