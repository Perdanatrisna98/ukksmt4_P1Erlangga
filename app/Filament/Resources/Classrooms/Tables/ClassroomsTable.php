<?php

namespace App\Filament\Resources\Classrooms\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ClassroomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('major.name')
                    ->label('Jurusan')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('level')
                    ->label('Tingkat')
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        10 => 'Kelas X',
                        11 => 'Kelas XI',
                        12 => 'Kelas XII',
                        default => "Kelas {$state}",
                    })
                    ->badge()
                    ->color(fn ($state) => match ((int) $state) {
                        10 => 'success',
                        11 => 'warning',
                        12 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('students_count')
                    ->label('Siswa')
                    ->counts('students')
                    ->suffix(' siswa')
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('level')
            ->striped()
            ->filters([
                SelectFilter::make('major_id')
                    ->label('Jurusan')
                    ->relationship('major', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('level')
                    ->label('Tingkat')
                    ->options([
                        10 => 'Kelas X',
                        11 => 'Kelas XI',
                        12 => 'Kelas XII',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif saja')
                    ->falseLabel('Nonaktif saja')
                    ->native(false),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->requiresConfirmation(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }
}