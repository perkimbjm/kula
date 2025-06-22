<?php

namespace Database\Factories;

use App\Models\Work;
use App\Models\Officer;
use App\Enums\TrackStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate random progress checkboxes
        $progressFields = [
            'survei', 'pemilihan', 'kontrak', 'uang_muka', 'kritis',
            'selesai', 'pho', 'aset', 'ppk_dinas', 'bendahara',
            'pengguna_anggaran', 'keuangan', 'bank', 'laporan'
        ];

        $progress = [];
        $completedCount = $this->faker->numberBetween(0, count($progressFields));

        // Set progress sequentially (realistic workflow)
        for ($i = 0; $i < count($progressFields); $i++) {
            $progress[$progressFields[$i]] = $i < $completedCount;
        }

        // Get random officers for pemeriksa
        $officers = Officer::pluck('id')->toArray();
        $selectedOfficers = $this->faker->randomElements($officers, $this->faker->numberBetween(1, min(3, count($officers))));

        return [
            'work_id' => Work::factory(),
            ...$progress,
            'pemeriksa' => $selectedOfficers,
            'lat' => $this->faker->latitude(-3.5, -1.5), // Kalimantan area
            'lng' => $this->faker->longitude(114, 117), // Kalimantan area
            'panjang' => $this->faker->randomFloat(2, 10, 1000),
            'lebar' => $this->faker->randomFloat(2, 1, 10),
            'foto_survey' => null, // Will be handled by file uploads
            'foto_pho' => null,
            'lampiran' => null,
            'status' => $this->faker->randomElement(TrackStatus::cases()),
            'catatan_tim_teknis' => $this->faker->optional(0.7)->paragraph(),
        ];
    }
}
