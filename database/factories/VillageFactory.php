<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\District;
use App\Models\Village;

class VillageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Village::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'geom' => $this->faker->text(),
            'code' => $this->faker->word(),
            'name' => $this->faker->name(),
            'district_id' => District::factory(),
            'slug' => $this->faker->slug(),
        ];
    }
}
