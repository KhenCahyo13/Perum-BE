<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;

class AuthDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@perum.test'],
            [
                'name'     => 'Admin Perumahan',
                'email'    => 'admin@perum.test',
                'password' => Hash::make('password'),
            ]
        );
    }
}
