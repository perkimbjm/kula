<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ConsolSpv;
use App\Models\ConsolSpvDetail;

class ConsolSpvDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsolSpvDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'consolidation_id' => ConsolSpv::factory(),
            'budget' => $this->faker->randomFloat(0, 0, 9999999999.),
            'name' => $this->faker->name(),
            'nego_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'consol_spv_id' => ConsolSpv::factory(),
        ];
    }
}
