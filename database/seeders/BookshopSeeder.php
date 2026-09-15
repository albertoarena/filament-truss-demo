<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Review;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Fixture rows for the bookshop.
 *
 * **These exist for the panel, never for the diagram.** The schema page is built
 * from structure, so it draws exactly the same picture against an empty database
 * as against this one. That is the demonstration rather than a caveat: browse the
 * books, then open the schema page and find the same tables, columns and keys
 * drawn from the database itself. If a row ever reaches the diagram, the package
 * has broken its one promise.
 *
 * Every relationship in the migration is exercised, because a relationship with
 * no rows behind it is a claim rather than a demonstration: the pivot, the
 * polymorphic pair, the self-referencing mentor, and the nullable reviewer.
 */
class BookshopSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = Publisher::factory(5)->create();

        $mentors = Author::factory(4)->create();
        $authors = Author::factory(10)
            ->sequence(fn ($sequence) => [
                // Two thirds have a mentor, so the self-referencing key has both
                // kinds of row in it.
                'mentor_id' => $sequence->index % 3 === 0 ? null : $mentors->random()->id,
            ])
            ->create()
            ->concat($mentors);

        $tags = collect(['fiction', 'reference', 'translated', 'illustrated', 'out-of-copyright', 'signed'])
            ->map(fn (string $name) => Tag::create(['name' => $name]));

        $readers = User::factory(6)->create();

        Book::factory(24)
            ->sequence(fn ($sequence) => ['publisher_id' => $publishers->random()->id])
            ->create()
            ->each(function (Book $book) use ($authors, $tags, $readers): void {
                $book->authors()->attach(
                    $authors->random(fake()->numberBetween(1, 3))->pluck('id')
                );

                $book->tags()->attach($tags->random(fake()->numberBetween(0, 3))->pluck('id'));

                Review::factory(fake()->numberBetween(0, 5))
                    ->sequence(fn ($sequence) => [
                        'book_id' => $book->id,
                        // Nullable, and left null for some, because that is the
                        // case the schema allows and the one worth seeing.
                        'user_id' => fake()->boolean(75) ? $readers->random()->id : null,
                    ])
                    ->create();
            });

        // Authors are taggable too, which is the half of a polymorphic pair that
        // is easy to forget and is exactly what the diagram shows.
        $authors->random(6)->each(
            fn (Author $author) => $author->tags()->attach($tags->random(fake()->numberBetween(1, 2))->pluck('id'))
        );
    }
}
