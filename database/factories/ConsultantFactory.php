<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Consultant;

class ConsultantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consultant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'akta' => $this->faker->word(),
            'founding_date' => $this->faker->date(),
            'notary' => $this->faker->word(),
            'address' => $this->faker->word(),
            'npwp' => $this->faker->word(),
            'leader' => $this->faker->word(),
            'position' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'account_number' => $this->faker->word(),
            'account_holder' => $this->faker->word(),
            'header_scan' => $this->faker->word(),
            'account_scan' => $this->faker->word(),
            'npwp_scan' => $this->faker->word(),
        ];
    }
}
