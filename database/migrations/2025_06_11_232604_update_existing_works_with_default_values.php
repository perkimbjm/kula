<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing works with default values for required fields
        DB::statement("
            UPDATE works
            SET
                village_id = (SELECT id FROM villages LIMIT 1),
                rt = '001',
                length = 100.00,
                phone = '081234567890',
                construction_type = 'Tidak Ditentukan'
            WHERE
                village_id IS NULL
                OR rt IS NULL
                OR length IS NULL
                OR phone IS NULL
                OR construction_type IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena data sudah diupdate
    }
};
