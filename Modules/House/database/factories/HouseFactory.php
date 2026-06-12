<?php

namespace Modules\House\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\House\Models\House;
use Modules\House\Models\HouseResidentHistory;
use Modules\House\Models\Resident;

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
            'address'      => fake()->streetAddress(),
            'status'       => fake()->randomElement(['occupied', 'vacant']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (House $house) {
            $periods = [
                [
                    'start' => now()->subYears(3),
                    'end'   => now()->subYears(2),
                ],
                [
                    'start' => now()->subYears(2)->addDay(),
                    'end'   => now()->subYear(),
                ],
                [
                    'start' => now()->subYear()->addDay(),
                    'end'   => null,
                ],
            ];

            foreach ($periods as $index => $period) {
                $isLast   = $index === 2;
                $isActive = $isLast && $house->status->toRaw() === 'occupied';

                HouseResidentHistory::create([
                    'house_id'    => $house->id,
                    'resident_id' => Resident::factory()->create()->id,
                    'start_date'  => $period['start']->toDateString(),
                    'end_date'    => $isActive ? null : ($period['end']?->toDateString() ?? now()->subDay()->toDateString()),
                    'is_active'   => $isActive,
                ]);
            }
        });
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
