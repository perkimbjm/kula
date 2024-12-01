<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Consultant;
use App\Models\Contractor;
use App\Models\District;
use App\Models\Facility;
use App\Models\Village;

class FacilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'contractor_id' => Contractor::factory(),
            'consultant_id' => Consultant::factory(),
            'district_id' => District::factory(),
            'village_id' => Village::factory(),
            'length' => $this->faker->randomFloat(0, 0, 9999999999.),
            'width' => $this->faker->randomFloat(0, 0, 9999999999.),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'real_1' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_2' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_3' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_4' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_5' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_6' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_7' => $this->faker->randomFloat(0, 0, 9999999999.),
            'real_8' => $this->faker->randomFloat(0, 0, 9999999999.),
            'photo_0' => $this->faker->word(),
            'photo_50' => $this->faker->word(),
            'photo_100' => $this->faker->word(),
            'photo_pho' => $this->faker->word(),
            'note' => $this->faker->text(),
            'note_pho' => $this->faker->text(),
            'team' => '{}',
            'construct_type' => $this->faker->word(),
            'spending_type' => $this->faker->word(),
        ];
    }
}
