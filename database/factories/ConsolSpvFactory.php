<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ConsolSpv;
use App\Models\Consultant;
use App\Models\ProcurementOfficer;

class ConsolSpvFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsolSpv::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'year' => $this->faker->numberBetween(-10000, 10000),
            'procurement_officer_id' => ProcurementOfficer::factory(),
            'bid_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'correction_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'nego_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'consultant_id' => Consultant::factory(),
            'invite_date' => $this->faker->date(),
            'evaluation_date' => $this->faker->date(),
            'nego_date' => $this->faker->date(),
            'BAHPL_date' => $this->faker->date(),
            'sppbj_date' => $this->faker->date(),
            'spk_date' => $this->faker->date(),
            'account_type' => $this->faker->word(),
            'program' => $this->faker->word(),
            'duration' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
