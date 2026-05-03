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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm'  => 1,
                'md'  => 2,
                'lg'  => 3,
                'xl'  => 3,
                '2xl' => 4,
            ])
            ->columns([
                Grid::make(['default' => 1])
                    ->schema([
                        ImageColumn::make('image')
                            ->height(160)
                            ->extraImgAttributes([
                                'style' => 'width:100%; object-fit:cover; border-radius:12px 12px 0 0;',
                            ])
                            ->defaultImageUrl(
                                fn ($record) =>
                                    'https://ui-avatars.com/api/?' . http_build_query([
                                        'name'       => $record->name ?? 'C',
                                        'background' => '1D9E75',
                                        'color'      => 'ffffff',
                                        'bold'       => 'true',
                                        'size'       => '200',
                                        'font-size'  => '0.4',
                                    ])
                            ),

                        Stack::make([
                            TextColumn::make('name')
                                ->label('Category Name')
                                ->searchable()
                                ->sortable()
                                ->weight(FontWeight::SemiBold)
                                ->size('md'),

                            TextColumn::make('assets_count')
                                ->label('Assets')
                                ->counts('assets')
                                ->prefix('  ')
                                ->suffix(' alat')
                                ->color('gray')
                                ->size('sm'),

                            TextColumn::make('is_active')
                                ->label('Status')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                                ->color(fn ($state) => $state ? 'success' : 'danger'),

                            TextColumn::make('created_at')
                                ->label('Created')
                                ->dateTime('d M Y, H:i')
                                ->size('sm')
                                ->toggleable(isToggledHiddenByDefault: true),
                        ])
                        ->space(2)
                        ->extraAttributes([
                            'class' => 'p-4',
                        ]),
                    ])
                    ->extraAttributes([
                        'class' => implode(' ', [
                            'rounded-2xl overflow-hidden',
                            'border border-gray-200 dark:border-gray-700',
                            'bg-white dark:bg-gray-800',
                            'shadow-sm hover:shadow-md',
                            'transition-all duration-200',
                            'cursor-pointer',
                        ]),
                    ]),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}