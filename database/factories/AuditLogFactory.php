<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AuditLog;
use App\Models\Model;
use App\Models\User;

class AuditLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AuditLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => $this->faker->word(),
            'model_type' => $this->faker->word(),
            'model_id' => Model::factory(),
            'ip_address' => $this->faker->word(),
        ];
    }
}
