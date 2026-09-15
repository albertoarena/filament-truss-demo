<?php

namespace App\Filament\Resources\Books\Tables;

use App\Enums\BookStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('publisher.name')
                    ->label('Publisher')
                    ->searchable()
                    ->sortable(),
                // Through `author_book`, the pivot that gets no resource of its
                // own and is managed from the relation manager instead.
                TextColumn::make('authors.name')
                    ->label('Authors')
                    ->badge()
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('isbn')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('reviews_count')
                    ->label('Reviews')
                    ->counts('reviews')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title')
            ->filters([
                SelectFilter::make('status')
                    ->options(BookStatus::class),
                SelectFilter::make('publisher')
                    ->relationship('publisher', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
