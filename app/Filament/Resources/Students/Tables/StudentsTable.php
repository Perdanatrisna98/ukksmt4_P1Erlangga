<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentsTable
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
                        ImageColumn::make('profile_picture')
                            ->disk('public')
                            ->imageHeight(180)
                            ->extraImgAttributes([
                                'style' => 'width:100%; object-fit:cover; border-radius:12px 12px 0 0;',
                            ])
                            ->defaultImageUrl(
                                fn ($record) =>
                                    'https://ui-avatars.com/api/?' . http_build_query([
                                        'name'       => $record->user?->name ?? 'S',
                                        'background' => '6366f1',
                                        'color'      => 'ffffff',
                                        'bold'       => 'true',
                                        'size'       => '200',
                                        'font-size'  => '0.4',
                                    ])
                            ),

                        Stack::make([
                            TextColumn::make('user.name')
                                ->label('Nama Siswa')
                                ->searchable()
                                ->sortable()
                                ->weight(FontWeight::SemiBold)
                                ->size('md'),

                            TextColumn::make('nisn')
                                ->label('NISN')
                                ->searchable()
                                ->icon('heroicon-o-identification')
                                ->size('sm'),

                            TextColumn::make('classroom.name')
                                ->label('Kelas')
                                ->searchable()
                                ->sortable()
                                ->icon('heroicon-o-building-office')
                                ->size('sm'),

                            TextColumn::make('phone_number')
                                ->label('No. HP')
                                ->searchable()
                                ->icon('heroicon-o-phone')
                                ->size('sm'),

                            TextColumn::make('gender')
                                ->label('Jenis Kelamin')
                                ->badge(),
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
                        ]),
                    ]),
            ])
            ->filters([
                SelectFilter::make('classroom_id')
                    ->label('Kelas')
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male'   => 'Laki-laki',
                        'female' => 'Perempuan',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}