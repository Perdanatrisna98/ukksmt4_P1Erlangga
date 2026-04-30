<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                '2xl' => 4,
                'xl'  => 3,
                'lg'  => 3,
                'md'  => 2,
                'sm'  => 1,
            ])
            ->columns([
                Grid::make(['default' => 1])
                    ->schema([
                        ImageColumn::make('image')
                            ->height(200)
                            ->extraImgAttributes([
                                'style' => 'width:100%; border-radius:12px 12px 0 0;',
                            ])
                            ->defaultImageUrl(
                                fn ($record) =>
                                'https://ui-avatars.com/api/?name=' .
                                urlencode($record->name ?? 'C') .
                                '&background=f59e0b&color=ffffff&bold=true&size=200'
                            ),

                        Stack::make([
                            TextColumn::make('name')
                                ->label('Nama Kategori')
                                ->searchable()
                                ->weight(FontWeight::Bold)
                                ->size('lg'),
                            TextColumn::make('is_active')
                                ->label('Status')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                                ->color(fn ($state) => $state ? 'success' : 'danger'),
                            TextColumn::make('created_at')
                                ->dateTime()
                                ->sortable()
                                ->dateTime('d M Y, H:i')
                                ->toggleable(isToggledHiddenByDefault: true),

                        ])
                        ->space(3)
                        ->extraAttributes([
                            'class' => 'p-5',
                        ]),
                    ])
                    ->extraAttributes([
                        'class' => 'rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200 bg-white dark:bg-gray-800',
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}