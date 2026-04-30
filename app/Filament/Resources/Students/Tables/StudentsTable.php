<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
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

                        ImageColumn::make('profile_picture')
                            ->disk('public')
                            ->height(180)
                            ->extraImgAttributes([
                                'style' => 'width:100%; border-radius:12px 12px 0 0;',
                            ])
                            ->defaultImageUrl(
                                fn ($record) =>
                                'https://ui-avatars.com/api/?name=' .
                                urlencode($record->user?->name ?? 'S') .
                                '&background=6366f1&color=ffffff&bold=true&size=200'
                            ),

                        Stack::make([
                            TextColumn::make('user.name')
                                ->label('Nama Siswa')
                                ->searchable()
                                ->sortable()
                                ->weight(FontWeight::Bold)
                                ->size('lg'),
                            TextColumn::make('nisn')
                                ->label('NISN')
                                ->searchable()
                                ->icon(Heroicon::Identification)
                                ->size('sm'),
                            TextColumn::make('classroom.name')
                                ->label('Kelas')
                                ->searchable()
                                ->sortable()
                                ->icon(Heroicon::BuildingOffice)
                                ->size('sm'),
                            TextColumn::make('phone_number')
                                ->label('No. HP')
                                ->searchable()
                                ->icon(Heroicon::Phone)
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
                        'class' => 'rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200 bg-white dark:bg-gray-800',
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