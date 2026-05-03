<?php

namespace App\Filament\Resources\Users\Tables;

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

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')   // Fix: ganti Heroicon::Envelope
                    ->copyable()
                    ->copyMessage('Email disalin!'),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'admin', 'super_admin' => 'danger',
                        default => 'primary',
                    }),

                IconColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->boolean()
                    ->getStateUsing(fn ($record) => ! is_null($record->email_verified_at))
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(
                        fn ($record) => $record->email_verified_at
                            ? 'Verified: ' . $record->email_verified_at->format('d M Y, H:i')
                            : 'Belum verifikasi'
                    ),

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
            ->defaultSort('name')
            ->striped()
            ->filters([
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('email_verified_at')
                    ->label('Status Verifikasi')
                    ->nullable()
                    ->trueLabel('Sudah verifikasi')
                    ->falseLabel('Belum verifikasi')
                    ->native(false),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->requiresConfirmation(),
                ])
                ->tooltip('Aksi')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }
}