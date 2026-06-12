<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Bill\Database\Seeders\BillDatabaseSeeder;
use Modules\Expense\Database\Seeders\ExpenseDatabaseSeeder;
use Modules\House\Database\Seeders\HouseDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AuthDatabaseSeeder::class,
            HouseDatabaseSeeder::class,
            BillDatabaseSeeder::class,
            ExpenseDatabaseSeeder::class,
        ]);
    }
}
