<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Consultant;
use App\Models\Plan;
use App\Models\ProcurementOfficer;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'contract_number' => 'SPK-PLAN-' . str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT) . '/' . $this->faker->randomElement(['APBD', 'APBN']) . '/' . date('Y'),
            'program' => $this->faker->sentence(4),
            'procurement_officer_id' => ProcurementOfficer::factory(),
            'duration' => $this->faker->numberBetween(30, 365),
            'oe' => $this->faker->randomFloat(0, 100000000, 5000000000),
            'bid_value' => $this->faker->randomFloat(0, 100000000, 5000000000),
            'correction_value' => $this->faker->randomFloat(0, 100000000, 5000000000),
            'nego_value' => $this->faker->randomFloat(0, 100000000, 5000000000),
            'consultant_id' => Consultant::factory(),
            'invite_date' => $this->faker->date(),
            'evaluation_date' => $this->faker->date(),
            'nego_date' => $this->faker->date(),
            'BAHPL_date' => $this->faker->date(),
            'sppbj_date' => $this->faker->date(),
            'spk_date' => $this->faker->date(),
            'account_type' => $this->faker->randomElement(['APBD', 'APBN', 'Dana Desa', 'PNBP']),
            'year' => $this->faker->numberBetween(2020, 2025),
            'addendum_number' => $this->faker->optional()->regexify('ADD-[0-9]{3}/[A-Z]{4}/[0-9]{4}'),
            'payment_date' => $this->faker->optional()->date(),
            'payment_value' => $this->faker->optional()->randomFloat(0, 1000000, 100000000),
            'ba_lkpp' => $this->faker->optional()->regexify('BA-LKPP-[0-9]{3}/[A-Z]{4}/[0-9]{4}'),
        ];
    }
}
