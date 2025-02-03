<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officers = [
            ['name' => 'MUHAMAD RIFANI, A.Md', 'nip' => '197612122005011010', 'grade' => null],
            ['name' => 'NINA YASARI, SE', 'nip' => '198310082010012022', 'grade' => null],
            ['name' => 'BUGI RUSDIANA, A. Md', 'nip' => '198803212010011002', 'grade' => null],
            ['name' => 'RIZKY RAHMAN, SE', 'nip' => '198601142006041002', 'grade' => null],
            ['name' => 'RACHMADI SANTOSO, ST', 'nip' => '198411152009041004', 'grade' => null],            
            ['name' => 'M. IRWAN LAFONI, ST', 'nip' => '197005241997031007', 'grade' => null],
            ['name' => 'EVA NISVIATI, SE', 'nip' => '198305272007012003', 'grade' => null],
            ['name' => 'Dra. Hj. ELYZA NURLIATI', 'nip' => '196708092006042012', 'grade' => null],
            ['name' => 'YULIASANTI, A. Md', 'nip' => '198007222010012002', 'grade' => null],
            ['name' => 'SELLY ANDIANI, ST', 'nip' => '199501012019032011', 'grade' => null],
            ['name' => 'NADIYA ADLINA, S. Ars', 'nip' => '199211222020122015', 'grade' => null],
            ['name' => 'YULISA MELLA, ST', 'nip' => '199107022020122017', 'grade' => null],
            ['name' => 'MUHAMMAD APIPUDIN, SE', 'nip' => '199707152022021003', 'grade' => null],
            ['name' => 'FERRY BOYCE DEPARI, A.Md', 'nip' => '198007172010011012', 'grade' => null],
        ];

        DB::table('officers')->insert($officers);
    }
}
