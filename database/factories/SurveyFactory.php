<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\District;
use App\Models\Survey;
use App\Models\Village;

class SurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Survey::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'supervisor' => $this->faker->word(),
            'district_id' => District::factory(),
            'village_id' => Village::factory(),
            'length' => $this->faker->randomFloat(0, 0, 9999999999.),
            'type' => $this->faker->word(),
            'program' => $this->faker->word(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'note' => $this->faker->text(),
        ];
    }
}
