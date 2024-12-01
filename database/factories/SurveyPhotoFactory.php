<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Survey;
use App\Models\SurveyPhoto;

class SurveyPhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SurveyPhoto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'survey_id' => Survey::factory(),
            'description' => $this->faker->text(),
            'photo_url' => $this->faker->word(),
        ];
    }
}
