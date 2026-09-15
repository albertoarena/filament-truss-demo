<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable(['name'])]
class Tag extends Model
{
    use HasFactory;

    public $timestamps = false;

    /** Polymorphic, through `taggables`: the same tag reaches two tables. */
    public function books(): MorphToMany
    {
        return $this->morphedByMany(Book::class, 'taggable');
    }

    public function authors(): MorphToMany
    {
        return $this->morphedByMany(Author::class, 'taggable');
    }
}
