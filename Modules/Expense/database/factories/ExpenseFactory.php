<?php

namespace Modules\Expense\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expense\Models\Expense;
use Modules\Expense\Models\ExpenseCategory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'category_id' => ExpenseCategory::factory(),
            'description' => fake()->optional(0.6)->sentence(),
            'amount' => fake()->numberBetween(50000, 5000000),
            'date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'is_recurring' => fake()->boolean(30),
        ];
    }

    public function recurring(): static
    {
        return $this->state(['is_recurring' => true]);
    }

    public function oneTime(): static
    {
        return $this->state(['is_recurring' => false]);
    }

    public function forCategory(ExpenseCategory $category): static
    {
        return $this->state(['category_id' => $category->id]);
    }
}
