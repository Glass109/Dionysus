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
        ]);

        User::factory()->count(2)->create();
    }
}
