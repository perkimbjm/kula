<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Consultant;
use App\Models\Contractor;
use App\Models\Work;

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
            'year' => $this->faker->numberBetween(-10000, 10000),
            'name' => $this->faker->name(),
            'contract_date' => $this->faker->date(),
            'contract_number' => $this->faker->word(),
            'contractor_id' => Contractor::factory(),
            'consultant_id' => Consultant::factory(),
            'supervisor_id' => Consultant::factory(),
            'contract_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'progress' => $this->faker->randomFloat(0, 0, 9999999999.),
            'cutoff' => $this->faker->date(),
            'status' => $this->faker->word(),
            'paid' => $this->faker->randomFloat(0, 0, 9999999999.),
        ];
    }
}
