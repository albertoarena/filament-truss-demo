<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['book_id', 'user_id', 'rating', 'body'])]
class Review extends Model
{
    use HasFactory;

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /** Nullable on purpose: a review outlives the account that wrote it. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
