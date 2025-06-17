<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Consultant;
use App\Models\Contractor;
use App\Models\Work;
use App\Models\District;
use App\Models\Village;
use App\Models\ProcurementOfficer;

class WorkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Work::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'year' => $this->faker->numberBetween(2020, 2025),
            'name' => $this->faker->sentence(3),
            'contract_date' => $this->faker->date(),
            'contract_number' => $this->faker->bothify('SPK-###/??/###'),
            'contractor_id' => Contractor::factory(),
            'consultant_id' => Consultant::factory(),
            'supervisor_id' => Consultant::factory(),
            'contract_value' => $this->faker->randomFloat(2, 100000000, 5000000000),
            'progress' => $this->faker->randomFloat(2, 0, 100),
            'cutoff' => $this->faker->date(),
            'status' => $this->faker->randomElement(['belum_kontrak', 'kontrak', 'selesai']),
            'paid' => $this->faker->randomFloat(2, 0, 1000000000),
            'district_id' => District::factory(),
            'village_id' => Village::factory(),
            'rt' => $this->faker->numberBetween(1, 20),
            'length' => $this->faker->randomFloat(2, 100, 5000),
            'width' => $this->faker->randomFloat(2, 3, 15),
            'phone' => $this->faker->phoneNumber(),
            'construction_type' => $this->faker->randomElement(['Jalan Beton', 'Jalan Aspal', 'Drainase', 'Jembatan']),
            'coordinate_lat' => $this->faker->optional()->latitude(-8, -6),
            'coordinate_lng' => $this->faker->optional()->longitude(110, 115),
            'account_code' => $this->faker->optional()->bothify('##.##.##'),
            'program' => $this->faker->optional()->randomElement(['P4K', 'DAK', 'APBD']),
            'source' => $this->faker->optional()->randomElement(['APBN', 'APBD', 'DAK']),
            'duration' => $this->faker->optional()->numberBetween(3, 12),
            'technical_team' => $this->faker->optional()->words(3),
            'procurement_officer_id' => $this->faker->optional()->randomElement([null, ProcurementOfficer::factory()]),
            'hps' => $this->faker->optional()->randomFloat(2, 100000000, 5000000000),
            'bid_value' => $this->faker->optional()->randomFloat(2, 100000000, 5000000000),
            'correction_value' => $this->faker->optional()->randomFloat(2, 0, 100000000),
            'nego_value' => $this->faker->optional()->randomFloat(2, 100000000, 5000000000),
            'invite_date' => $this->faker->optional()->date(),
            'evaluation_date' => $this->faker->optional()->date(),
            'nego_date' => $this->faker->optional()->date(),
            'bahpl_date' => $this->faker->optional()->date(),
            'sppbj_date' => $this->faker->optional()->date(),
            'spk_date' => $this->faker->optional()->date(),
            'addendum_date' => $this->faker->optional()->date(),
            'addendum_value' => $this->faker->optional()->randomFloat(2, 0, 1000000000),
            'completion_letter' => $this->faker->optional()->bothify('Surat-###/??/###'),
            'completion_date' => $this->faker->optional()->date(),
            'pho_date' => $this->faker->optional()->date(),
            'advance_bap_number' => $this->faker->optional()->bothify('BAP-UM-###/??/###'),
            'advance_guarantee_number' => $this->faker->optional()->bothify('JUM-###/??/###'),
            'advance_guarantor' => $this->faker->optional()->company(),
            'advance_guarantee_date' => $this->faker->optional()->date(),
            'advance_value' => $this->faker->optional()->randomFloat(2, 10000000, 500000000),
            'advance_payment_date' => $this->faker->optional()->date(),
            'final_bap_number' => $this->faker->optional()->bothify('BAP-FIN-###/??/###'),
            'maintenance_guarantee_number' => $this->faker->optional()->bothify('JMP-###/??/###'),
            'final_guarantor' => $this->faker->optional()->company(),
            'final_guarantee_date' => $this->faker->optional()->date(),
            'final_guarantee_value' => $this->faker->optional()->randomFloat(2, 10000000, 500000000),
            'final_payment_date' => $this->faker->optional()->date(),
        ];
    }
}
