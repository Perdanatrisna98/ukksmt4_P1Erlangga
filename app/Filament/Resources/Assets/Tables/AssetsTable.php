<?php

namespace App\Filament\Resources\Assets\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
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
                ColumnGroup::make('Detail Alat', [
                    ImageColumn::make('image')
                        ->label('Gambar')
                        ->disk('public')
                        ->imageSize(100),

                    TextColumn::make('name')
                        ->label('Nama Alat')
                        ->searchable()
                        ->sortable()
                        ->weight('medium'),

                    TextColumn::make('code')
                        ->label('Kode')
                        ->searchable(),

                    TextColumn::make('category.name')
                        ->label('Kategori')
                        ->sortable()
                        ->badge()
                        ->color('info')
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),

                ColumnGroup::make('Kondisi Alat', [  // Fix: typo "Conditiomn"
                    TextColumn::make('good_qty')
                        ->label('Bagus')
                        ->numeric()
                        ->color('success'),

                    TextColumn::make('damaged_qty')
                        ->label('Rusak')
                        ->numeric()
                        ->color('warning'),

                    TextColumn::make('borrowed_qty')
                        ->label('Dipinjam')
                        ->numeric()
                        ->color('info'),

                    TextColumn::make('lost_qty')
                        ->label('Hilang')
                        ->numeric()
                        ->color('danger'),

                    TextColumn::make('total_qty')
                        ->label('Total')
                        ->numeric()
                        ->badge()
                        ->weight('medium'),

                    TextColumn::make('available_qty')
                        ->label('Tersedia')
                        ->getStateUsing(fn ($record) => $record->good_qty - $record->borrowed_qty)
                        ->badge()
                        ->color(fn ($state): string => match (true) {
                            $state > 5  => 'success',
                            $state > 0  => 'warning',
                            default     => 'danger',
                        }),
                ]),

                IconColumn::make('is_available')
                    ->label('Available?')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()                    // tampil "2 days ago"
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->striped()
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_available')
                    ->label('Availability')
                    ->trueLabel('Available only')
                    ->falseLabel('Unavailable only')
                    ->native(false),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->requiresConfirmation(),
                ])
                ->tooltip('Actions')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}