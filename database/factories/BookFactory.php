<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BookStatus;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Book> */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'publisher_id' => Publisher::factory(),
            'title' => rtrim(ucwords(fake()->words(fake()->numberBetween(1, 4), true)), '.'),
            'isbn' => fake()->unique()->isbn13(),
            'price' => fake()->randomFloat(2, 4, 89),
            'status' => fake()->randomElement(BookStatus::cases()),
        ];
    }
}
