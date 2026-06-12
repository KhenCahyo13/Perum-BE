<?php

namespace Modules\Expense\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Expense\Models\Expense;
use Modules\Expense\Models\ExpenseCategory;

class ExpenseDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Gaji Satpam'],
            ['name' => 'Token Listrik'],
            ['name' => 'Perbaikan Jalan'],
            ['name' => 'Perbaikan Selokan'],
            ['name' => 'Kebersihan Lingkungan'],
            ['name' => 'Perlengkapan Pos'],
            ['name' => 'Lainnya'],
        ])->map(fn ($data) => ExpenseCategory::create($data));

        $expenseMap = [
            'Gaji Satpam'           => ['amount' => 2500000, 'recurring' => true,  'desc' => 'Gaji satpam bulan ini'],
            'Token Listrik'         => ['amount' => 350000,  'recurring' => true,  'desc' => 'Token listrik area umum'],
            'Perbaikan Jalan'       => ['amount' => 1500000, 'recurring' => false, 'desc' => 'Pengecoran jalan blok B'],
            'Perbaikan Selokan'     => ['amount' => 800000,  'recurring' => false, 'desc' => 'Pembersihan dan perbaikan selokan'],
            'Kebersihan Lingkungan' => ['amount' => 200000,  'recurring' => true,  'desc' => 'Biaya kebersihan bulanan'],
            'Perlengkapan Pos'      => ['amount' => 150000,  'recurring' => false, 'desc' => 'Pembelian perlengkapan pos satpam'],
            'Lainnya'               => ['amount' => 300000,  'recurring' => false, 'desc' => null],
        ];

        foreach ($categories as $category) {
            $map = $expenseMap[$category->name];

            for ($monthsAgo = 11; $monthsAgo >= 0; $monthsAgo--) {
                $date = now()->startOfMonth()->subMonths($monthsAgo)->addDays(rand(1, 20));

                Expense::create([
                    'category_id'  => $category->id,
                    'description'  => $map['desc'],
                    'amount'       => $map['amount'] + rand(-20000, 20000),
                    'date'         => $date->toDateString(),
                    'is_recurring' => $map['recurring'],
                ]);
            }
        }
    }
}
