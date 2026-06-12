<?php

namespace Modules\House\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\House\Models\House;
use Modules\House\Models\HouseResidentHistory;
use Modules\House\Models\Resident;

/**
 * @extends Factory<HouseResidentHistory>
 */
class HouseResidentHistoryFactory extends Factory
{
    protected $model = HouseResidentHistory::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-2 years', '-3 months');

        return [
            'house_id' => House::factory(),
            'resident_id' => Resident::factory(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => null,
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(['end_date' => null, 'is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(function () {
            $endDate = fake()->dateTimeBetween('-2 months', 'now');

            return [
                'end_date' => $endDate->format('Y-m-d'),
                'is_active' => false,
            ];
        });
    }
}
