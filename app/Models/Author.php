<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable(['name', 'biography', 'mentor_id'])]
class Author extends Model
{
    use HasFactory;

    /** The self-referencing key, which is the one the diagram marks on the column. */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'mentor_id');
    }

    public function mentees(): HasMany
    {
        return $this->hasMany(self::class, 'mentor_id');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
