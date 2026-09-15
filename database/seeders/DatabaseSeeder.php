<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * The demo user, then a bookshop with rows in it.
     *
     * **The rows are for the panel, not for the diagram.** The schema page is
     * built from structure and draws the same picture against an empty database,
     * which is still the promise being kept. They are seeded because a panel with
     * no records cannot show that the page and the resources are looking at the
     * same database: browse the books, open the schema page, find those tables.
     * If a row ever reaches the diagram, the package has broken its one promise.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo Admin',
                'password' => 'password',
            ],
        );

        $this->call(BookshopSeeder::class);
    }
}
