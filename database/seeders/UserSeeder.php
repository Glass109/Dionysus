<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Raúl Dondé',
            'email' => 'glas109@gmail.com',
            'password' => Hash::make('password'),
            'subscription_tier' => 'PLATINUM',
            'role' => 'admin',
        ]);

        User::factory()->count(19)->create();
    }
}
