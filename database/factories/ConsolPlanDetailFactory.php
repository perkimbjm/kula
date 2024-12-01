<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ConsolPlan;
use App\Models\ConsolPlanDetail;

class ConsolPlanDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsolPlanDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'consolidation_id' => ConsolPlan::factory(),
            'budget' => $this->faker->randomFloat(0, 0, 9999999999.),
            'name' => $this->faker->name(),
            'nego_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'consol_plan_id' => ConsolPlan::factory(),
        ];
    }
}
