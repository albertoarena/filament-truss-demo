<?php

namespace App\Filament\Resources\Books\Schemas;

use App\Enums\BookStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('publisher_id')
                    ->relationship('publisher', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('isbn')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Select::make('status')
                    ->options(BookStatus::class)
                    ->default('draft')
                    ->required(),
            ]);
    }
}
