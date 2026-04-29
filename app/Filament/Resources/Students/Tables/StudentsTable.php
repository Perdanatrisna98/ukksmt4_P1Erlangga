<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_picture')
                    ->disk('public')
                    ->imageHeight(80),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Student Name')
                    ->sortable(),
                TextColumn::make('nisn')
                    ->searchable()
                    ->label('NISN'),
                TextColumn::make('classroom.name')
                    ->searchable()
                    ->label('Class')
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->searchable()
                    ->label('Phone Number'),
                TextColumn::make('gender')
                    ->badge()
                    ->label('Gender'),
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
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
