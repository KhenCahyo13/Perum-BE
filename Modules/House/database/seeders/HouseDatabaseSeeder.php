<?php

namespace Modules\House\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\House\Models\House;

class HouseDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        House::factory()->occupied()->count(10)->create();
        House::factory()->vacant()->count(5)->create();
    }
}
