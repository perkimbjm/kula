<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;

class TicketResponseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TicketResponse::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'admin_id' => User::factory(),
            'issue' => $this->faker->text(),
            'response' => $this->faker->text(),
            'user_id' => User::factory(),
        ];
    }
}
