<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition(): array
    {
        return [
            'owner_id' => 1,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start' => $this->faker->dateTimeBetween('now', '+1 year'),
            'end' => $this->faker->dateTimeBetween('now', '+1 year'),
            'location_name' => $this->faker->company,
            'location_address' => $this->faker->address,
            'location_url' => $this->faker->url,
            'image' => 'https://loremflickr.com/640/480/dog"',
            'color' => $this->faker->hexColor,
        ];
    }
}
