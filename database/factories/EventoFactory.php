<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class EventoFactory extends Factory
{
    protected $model = Evento::class;
    private array $ageGroups = ['kids', 'teens', 'young', 'adults', 'middle', 'seniors', 'all', 'other'];

    public function definition(): array
    {
        return [
            'owner_id' => 1,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start' => $this->faker->dateTimeBetween('now', '+1 year'),
            'end' => $this->faker->dateTimeBetween('now', '+1 year'),
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
