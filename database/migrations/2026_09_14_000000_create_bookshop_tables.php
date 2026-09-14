<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A schema that is worth drawing.
 *
 * Laravel's own tables are four boxes with almost no edges, which tells you
 * nothing about whether the diagram works. This adds a small bookshop with
 * enough relationships to exercise the things that actually break: one to many,
 * many to many through a pivot, a self-referencing key, a nullable key, a
 * composite unique index, and a polymorphic pair.
 *
 * Structure is the point here, not data. Nothing in this application ever needs
 * a row for the diagram to be correct.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country', 2)->nullable();
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('biography')->nullable();
            // Self-referencing: Truss marks these on the column rather than
            // drawing a loop, so it is worth having one to look at.
            $table->foreignId('mentor_id')->nullable()->constrained('authors');
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained();
            $table->string('title');
            $table->string('isbn', 17)->unique();
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'out_of_print'])->default('draft');
            $table->timestamps();
        });

        // A pivot, and a genuine one: two foreign keys, a composite unique key,
        // and nothing else of substance.
        Schema::create('author_book', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->unique(['author_id', 'book_id']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('body')->nullable();
            $table->timestamps();
        });

        // Polymorphic, which Truss treats as a case of its own: there is no one
        // table for a morph column to point at.
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->morphs('taggable');
            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('author_book');
        Schema::dropIfExists('books');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('publishers');
    }
};
