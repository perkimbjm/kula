<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOldColumnsFromFacilitiesTable extends Migration
{
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropForeign('facilities_contractor_id_foreign');
            $table->dropForeign('facilities_consultant_id_foreign');
            $table->dropForeign('facilities_district_id_foreign');
            $table->dropForeign('facilities_village_id_foreign');

            $table->dropColumn([
                'name',
                'contractor_id',
                'consultant_id',
                'district_id',
                'village_id',
                'note_pho',
                'spending_type',
                'team',
            ]);
        });
    }

    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->foreignId('contractor_id')->constrained();
            $table->foreignId('consultant_id')->constrained();
            $table->foreignId('district_id')->constrained();
            $table->foreignId('village_id')->constrained();
            $table->string('name')->nullable();
            $table->string('note_pho')->nullable();
            $table->string('spending_type')->nullable();
            $table->json('team')->nullable();

        });
    }
}
