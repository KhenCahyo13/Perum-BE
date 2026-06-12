<?php

namespace Modules\Bill\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bill\Models\FeeType;

/**
 * @extends Factory<FeeType>
 */
class FeeTypeFactory extends Factory
{
    protected $model = FeeType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Satpam', 'Kebersihan']),
            'amount' => fake()->randomElement([100000, 15000]),
        ];
    }

    public function satpam(): static
    {
        return $this->state(['name' => 'Satpam', 'amount' => 100000]);
    }

    public function kebersihan(): static
    {
        return $this->state(['name' => 'Kebersihan', 'amount' => 15000]);
    }
}
