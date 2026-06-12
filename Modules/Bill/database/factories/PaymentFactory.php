<?php

namespace Modules\Bill\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bill\Models\Bill;
use Modules\Bill\Models\Payment;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'bill_id' => Bill::factory()->paid(),
            'payment_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'amount' => fake()->randomElement([15000, 100000]),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function forBill(Bill $bill): static
    {
        return $this->state([
            'bill_id' => $bill->id,
            'amount' => $bill->feeType->amount,
        ]);
    }
}
