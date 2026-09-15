<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Publisher> */
class PublisherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'country' => fake()->randomElement(['GB', 'IT', 'US', 'FR', 'DE', null]),
        ];
    }
}
