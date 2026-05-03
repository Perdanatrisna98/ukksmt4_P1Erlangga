<?php

namespace App\Filament\Resources\Assets\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColumnGroup::make('Asset Detail',[
                    ImageColumn::make('image')
                        ->disk('public')
                        ->imageSize(50),
                    TextColumn::make('name')
                        ->searchable()
                        ->label('Asset Name'),
                    TextColumn::make('code')
                        ->searchable()
                        ->label('Code'),
                    TextColumn::make('category.name')
                        ->label('Category')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),

                ColumnGroup::make('Asset Conditiomn',[
                    TextColumn::make('good_qty')
                        ->numeric()
                        ->label('Good'),
                    TextColumn::make('damaged_qty')
                        ->numeric()
                        ->label('Damaged'),
                    TextColumn::make('borrowed_qty')
                        ->numeric()
                        ->label('Borrowed'),
                    TextColumn::make('lost_qty')
                        ->numeric()
                        ->label('Lost'),
                    TextColumn::make('total_qty')
                        ->numeric()
                        ->label('Total'),
                    TextColumn::make('available_qty')
                        ->numeric()
                        ->label('Available')
                        ->getStateUsing(fn($record)=>$record->good_qty - $record->borrowed_qty)
                        ->badge(),
                    ]),

                
                IconColumn::make('is_available')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_available')
                    ->label('Availability')
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
