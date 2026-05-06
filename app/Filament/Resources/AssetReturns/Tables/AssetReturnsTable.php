<?php

namespace App\Filament\Resources\AssetReturns\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket.ticket_number')    // Fix: bukan ticket_id
                    ->label('Nomor Tiket')
                    ->searchable()
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('Nomor tiket disalin!'),

                TextColumn::make('user.name')               // Fix: bukan user_id
                    ->label('Diverifikasi Oleh')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),

                TextColumn::make('asset.name')              // Fix: bukan asset_id
                    ->label('Aset')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-cube'),

                TextColumn::make('qty')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable()
                    ->suffix(' unit')
                    ->alignCenter(),

                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'good'    => 'Baik',
                        'damaged' => 'Rusak',
                        'lost'    => 'Hilang',
                        default   => ucfirst($state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'good'    => 'success',
                        'damaged' => 'warning',
                        'lost'    => 'danger',
                        default   => 'gray',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'good'    => 'heroicon-o-check-circle',
                        'damaged' => 'heroicon-o-wrench-screwdriver',
                        'lost'    => 'heroicon-o-exclamation-triangle',
                        default   => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('returned_at')
                    ->label('Waktu Pengembalian')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('returned_at', 'desc')
            ->striped()
            ->filters([
                SelectFilter::make('condition')
                    ->label('Kondisi')
                    ->options([
                        'good'    => 'Baik',
                        'damaged' => 'Rusak',
                        'lost'    => 'Hilang',
                    ])
                    ->native(false),

                SelectFilter::make('asset_id')
                    ->label('Aset')
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
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