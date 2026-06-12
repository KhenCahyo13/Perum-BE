<?php

namespace Modules\Expense\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expense\Models\ExpenseCategory;

/**
 * @extends Factory<ExpenseCategory>
 */
class ExpenseCategoryFactory extends Factory
{
    protected $model = ExpenseCategory::class;

    private static array $categories = [
        'Gaji Satpam',
        'Token Listrik',
        'Perbaikan Jalan',
        'Perbaikan Selokan',
        'Kebersihan Lingkungan',
        'Perlengkapan Pos',
        'Lainnya',
    ];

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(self::$categories),
        ];
    }
}
