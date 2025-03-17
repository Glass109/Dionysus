<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evento_id' => Evento::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'rating' => $this->faker->numberBetween(5, 10),
            'comment' => $this->faker->sentence,
        ];
    }
}
