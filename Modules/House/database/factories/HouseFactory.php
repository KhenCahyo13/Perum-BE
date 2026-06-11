<?php

namespace Modules\House\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\House\Models\House;

/**
 * @extends Factory<House>
 */
class HouseFactory extends Factory
{
    protected $model = House::class;

    public function definition(): array
    {
        return [
            'house_number' => fake()->unique()->bothify('Blok ?-##'),
            'address' => fake()->streetAddress(),
            'status' => fake()->randomElement(['occupied', 'vacant']),
        ];
    }

    public function occupied(): static
    {
        return $this->state(['status' => 'occupied']);
    }

    public function vacant(): static
    {
        return $this->state(['status' => 'vacant']);
    }
}
