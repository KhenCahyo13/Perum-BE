<?php

namespace Modules\House\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\House\Models\Resident;

/**
 * @extends Factory<Resident>
 */
class ResidentFactory extends Factory
{
    protected $model = Resident::class;

    public function definition(): array
    {
        return [
            'full_name'       => fake()->name(),
            'ktp_file_url'    => 'ktp/' . fake()->uuid() . '.jpg',
            'phone_number'    => fake()->unique()->numerify('08###########'),
            'is_married'      => fake()->boolean(),
            'resident_type'   => fake()->randomElement(['permanent', 'contract']),
        ];
    }

    public function permanent(): static
    {
        return $this->state(['resident_type' => 'permanent']);
    }

    public function contract(): static
    {
        return $this->state(['resident_type' => 'contract']);
    }

    public function married(): static
    {
        return $this->state(['is_married' => true]);
    }

    public function single(): static
    {
        return $this->state(['is_married' => false]);
    }
}
