<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProcurementOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officers = [
            ['name' => 'NETTY MURDIATI, ST', 'nip' => '197602132005012008', 'grade' => null],
            ['name' => 'ROSALINA, ST', 'nip' => '198210202010012026', 'grade' => null],
            ['name' => 'AKHMAD NOVIE, ST', 'nip' => '197811012006041008', 'grade' => null],
            ['name' => 'TONY HUTAGAOL, ST', 'nip' => '198911192022021001', 'grade' => null],
            ['name' => 'NORHIDAYATI, S.Ars', 'nip' => '197907122005012019', 'grade' => null],
            ['name' => 'NINA YASARI, SE', 'nip' => '198310082010012022', 'grade' => null],
            ['name' => 'RACHMADI SANTOSO, ST', 'nip' => '198411152009041004', 'grade' => null],            
            ['name' => 'M. IRWAN LAFONI, ST', 'nip' => '197005241997031007', 'grade' => null],
            ['name' => 'EVA NISVIATI, SE', 'nip' => '198305272007012003', 'grade' => null],
            ['name' => 'Dra. Hj. ELYZA NURLIATI', 'nip' => '196708092006042012', 'grade' => null],
        ];

        DB::table('procurement_officers')->insert($officers);
    }

}
