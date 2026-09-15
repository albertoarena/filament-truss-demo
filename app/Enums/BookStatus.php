<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * The `status` column of `books`, which is an enum in the database.
 *
 * Worth having as a PHP enum rather than three loose strings: the panel renders
 * it as a badge, and the diagram shows the same column as the database spells
 * it, which is a nice thing to be able to compare on one screen.
 */
enum BookStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case OutOfPrint = 'out_of_print';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::OutOfPrint => 'Out of print',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
            self::OutOfPrint => 'warning',
        };
    }
}
