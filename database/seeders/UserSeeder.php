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

        User::factory()->create([
            'name' => 'Invitado',
            'email' => 'invitado@dionysus.app',
            'password' => Hash::make('invitado'),
            'subscription_tier' => 'STANDARD',
        ]);

        User::factory()->count(18)->create();
    }
}
