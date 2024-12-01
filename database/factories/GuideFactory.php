<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Guide;

class GuideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guide::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'thumbnail' => $this->faker->word(),
            'description' => $this->faker->text(),
            'guide_url' => $this->faker->word(),
        ];
    }
}
