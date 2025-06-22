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
            // Drop indexes if they exist
            $indexes = ['works_status_year_index', 'works_contractor_id_status_index', 'works_progress_index'];

            foreach ($indexes as $index) {
                if (Schema::hasIndex('works', $index)) {
                    $table->dropIndex($index);
                }
            }

            // Drop columns
            $table->dropColumn(['progress', 'cutoff', 'paid', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            // Recreate columns
            $table->float('progress')->default(0)->after('contract_value');
            $table->date('cutoff')->after('progress');
            $table->string('status')->default('belum kontrak')->after('cutoff');
            $table->float('paid')->nullable()->after('status');

            // Recreate indexes
            $table->index(['status', 'year']);
            $table->index(['contractor_id', 'status']);
            $table->index(['progress']);
        });
    }
};
