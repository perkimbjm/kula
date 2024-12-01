<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\TicketFeedback;
use App\Models\User;

class TicketFeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TicketFeedback::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'feedback' => $this->faker->text(),
            'rating' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
