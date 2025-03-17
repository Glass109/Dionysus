<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EventoSeeder::class,
        ]);
        Artisan::call('events:recalculate-tiers');
    }
}
