<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Review> */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'user_id' => null,
            'rating' => fake()->numberBetween(1, 5),
            'body' => fake()->boolean(80) ? fake()->sentences(fake()->numberBetween(1, 3), true) : null,
        ];
    }
}
