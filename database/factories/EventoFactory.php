<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    protected $model = Evento::class;
    private array $ageGroups = ['kids', 'teens', 'young', 'adults', 'middle', 'seniors', 'all', 'other'];

    public function definition(): array
    {
        $owner = User::inRandomOrder()->first();
        return [
            'owner_id' => $owner->id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(0, 1000),
            'capacity' => $this->faker->numberBetween(1, 1000),
            'start' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'age_group' => $this->ageGroups[array_rand($this->ageGroups)],
            'location_name' => $this->faker->company,
            'location_address' => $this->faker->address,
            'location_url' => $this->faker->url,
            'image' => $this->getRandomEventImage(),
            'color' => $this->faker->hexColor,
        ];
    }

    /**
     * Get a random image from the public/eventPlaceholders directory
     */
    private function getRandomEventImage(): string
    {
        // Get all files in the directory
        $files = glob(public_path('eventPlaceholders/*'));

        // If no files found, return a default image
        if (empty($files)) {
            return 'https://loremflickr.com/640/480/dog';
        }

        // Select a random file
        $randomFile = $files[array_rand($files)];

        // Get the filename from the path
        $filename = basename($randomFile);

        // Return the public URL to the image
        return asset('eventPlaceholders/' . $filename);
    }
}
