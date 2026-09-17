<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A change after the bookshop was created, so the panel has something to show
 * in the "Changes" panel.
 *
 * Truss records the pre-migration schema as the baseline whenever migrations
 * finish, so the diagram reports whatever the most recent migration did. A demo
 * whose schema was built in one migration has nothing to compare against and
 * hides that panel entirely.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });
    }
};
