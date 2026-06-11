<?php

namespace Modules\Bill\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bill\Models\Bill;
use Modules\Bill\Models\FeeType;
use Modules\House\Models\House;
use Modules\House\Models\Resident;

/**
 * @extends Factory<Bill>
 */
class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        $billingMonth = fake()->dateTimeBetween('-1 year', 'now');
        $billingMonth->modify('first day of this month');

        return [
            'house_id'      => House::factory()->occupied(),
            'resident_id'   => Resident::factory(),
            'fee_type_id'   => FeeType::factory(),
            'billing_month' => $billingMonth->format('Y-m-d'),
            'due_date'      => $billingMonth->modify('last day of this month')->format('Y-m-d'),
            'status'        => fake()->randomElement(['unpaid', 'paid', 'late']),
        ];
    }

    public function paid(): static
    {
        return $this->state(['status' => 'paid']);
    }

    public function unpaid(): static
    {
        return $this->state(['status' => 'unpaid']);
    }

    public function late(): static
    {
        return $this->state(['status' => 'late']);
    }

    public function forHouse(House $house): static
    {
        return $this->state(['house_id' => $house->id]);
    }

    public function forResident(Resident $resident): static
    {
        return $this->state(['resident_id' => $resident->id]);
    }

    public function forFeeType(FeeType $feeType): static
    {
        return $this->state(['fee_type_id' => $feeType->id]);
    }
}
