<?php

namespace App\Filament\Resources\Tickets\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->fontFamily('mono'),

                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),

                TextColumn::make('asset.name')
                    ->label('Aset')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-cube'),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'booked'     => 'Dipesan',
                        'borrowed'   => 'Dipinjam',
                        'verifying'  => 'Dikembalikan',
                        'returned'   => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                        default      => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'booked'    => 'info',
                        'borrowed'  => 'warning',
                        'verifying' => 'warning',
                        'returned'  => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'booked'    => 'heroicon-o-clock',
                        'borrowed'  => 'heroicon-o-arrow-up-tray',
                        'verifying' => 'heroicon-o-magnifying-glass',
                        'returned'  => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default     => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('booked_at')
                    ->label('Dipesan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('borrowed_at')
                    ->label('Dipinjam')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('due_at')
                    ->label('Batas Kembali')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->color(fn ($record) => match (true) {
                        is_null($record->due_at) => null,
                        Carbon::parse($record->due_at)->isPast() && $record->status === 'borrowed' => 'danger',
                        Carbon::parse($record->due_at)->isToday() => 'warning',
                        default => null,
                    }),

                TextColumn::make('returned_at')
                    ->label('Dikembalikan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

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
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'booked'    => 'Dipesan',
                        'borrowed'  => 'Dipinjam',
                        'verifying' => 'Dikembalikan',
                        'returned'  => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->native(false),

                SelectFilter::make('asset_id')
                    ->label('Aset')
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('approveBorrowing')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman?')
                    ->modalDescription('Aset akan langsung dicatat sebagai dipinjam.')
                    ->visible(fn ($record) => $record->status === 'booked')
                    ->action(fn ($record) => $record->update([
                        'status'      => 'borrowed',
                        'borrowed_at' => now(),
                    ]))
                    ->button(),

                Action::make('cancelBorrowing')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Peminjaman?')
                    ->modalDescription('Status tiket akan diubah menjadi Dibatalkan.')
                    ->visible(fn ($record) => $record->status === 'booked')
                    ->action(fn ($record) => $record->update([
                        'status' => 'cancelled',
                    ]))
                    ->button(),

                Action::make('verifyReturn')
                    ->label('Tandai Kembali')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai sebagai Dikembalikan?')
                    ->modalDescription('Tiket akan masuk ke tahap verifikasi pengembalian.')
                    ->visible(fn ($record) => $record->status === 'borrowed')
                    ->action(fn ($record) => $record->update([
                        'status' => 'verifying',
                    ]))
                    ->button(),

                Action::make('completed')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pengembalian?')
                    ->modalDescription('Aset akan dicatat telah dikembalikan dan tiket ditutup.')
                    ->visible(fn ($record) => $record->status === 'verifying')
                    ->action(fn ($record) => $record->update([
                        'status'      => 'returned',
                        'returned_at' => now(),
                    ]))
                    ->button(),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->requiresConfirmation(),
                ])
                ->tooltip('Aksi lain')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }
}