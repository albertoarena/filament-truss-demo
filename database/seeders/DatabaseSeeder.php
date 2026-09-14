<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * One user, so the panel can be logged into.
     *
     * No other rows are seeded, and that is the point rather than laziness: the
     * diagram is built from structure, so an empty bookshop draws exactly the
     * same picture as a full one. If this application ever needs rows to make
     * the schema page look right, something has gone wrong in the package.
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
    }
}
