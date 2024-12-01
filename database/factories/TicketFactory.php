<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\District;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Village;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->word(),
            'issue' => $this->faker->text(),
            'district_id' => District::factory(),
            'village_id' => Village::factory(),
            'photo_url' => $this->faker->word(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'status' => $this->faker->word(),
        ];
    }
}
