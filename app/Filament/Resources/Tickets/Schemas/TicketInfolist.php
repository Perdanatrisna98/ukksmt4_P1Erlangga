<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Tiket')
                        ->icon('heroicon-o-ticket')
                        ->schema([
                            TextEntry::make('ticket_number')
                                ->label('No. Tiket')
                                ->fontFamily('mono')
                                ->copyable()
                                ->copyMessage('Nomor tiket disalin!')
                                ->columnSpanFull(),

                            TextEntry::make('user.name')
                                ->label('Peminjam')
                                ->icon('heroicon-o-user'),

                            TextEntry::make('asset.name')
                                ->label('Aset')
                                ->icon('heroicon-o-cube'),

                            TextEntry::make('qty')
                                ->label('Jumlah')
                                ->numeric()
                                ->suffix(' unit'),

                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'booked'    => 'Dipesan',
                                    'borrowed'  => 'Dipinjam',
                                    'verifying' => 'Dikembalikan',
                                    'returned'  => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default     => ucfirst($state),
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
                        ])
                        ->columns(2),

                    Section::make('Timeline Peminjaman')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            TextEntry::make('booked_at')
                                ->label('Dipesan')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-')
                                ->icon('heroicon-o-clock'),

                            TextEntry::make('borrowed_at')
                                ->label('Dipinjam')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('Belum dipinjam')
                                ->icon('heroicon-o-arrow-up-tray'),

                            TextEntry::make('due_at')
                                ->label('Batas Kembali')
                                ->date('d M Y')
                                ->placeholder('Belum ditentukan')
                                ->icon('heroicon-o-exclamation-circle')
                                ->color(fn ($record) => match (true) {
                                    is_null($record->due_at) => null,
                                    Carbon::parse($record->due_at)->isPast() && $record->status === 'borrowed' => 'danger',
                                    Carbon::parse($record->due_at)->isToday() => 'warning',
                                    default => null,
                                }),

                            TextEntry::make('returned_at')
                                ->label('Dikembalikan')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('Belum dikembalikan')
                                ->icon('heroicon-o-arrow-down-tray'),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Aset')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            TextEntry::make('asset.category.name')
                                ->label('Kategori')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('asset.code')
                                ->label('Kode Aset')
                                ->fontFamily('mono')
                                ->copyable()
                                ->copyMessage('Kode disalin!'),

                            TextEntry::make('asset.available_qty')
                                ->label('Stok Tersedia')
                                ->getStateUsing(
                                    fn ($record) => max(0, $record->asset->good_qty - $record->asset->borrowed_qty)
                                )
                                ->suffix(' unit')
                                ->badge()
                                ->color(fn ($state) => match (true) {
                                    $state > 5 => 'success',
                                    $state > 0 => 'warning',
                                    default => 'danger',
                                }),
                        ]),

                    Section::make('Timestamps')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Dibuat')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),

                            TextEntry::make('updated_at')
                                ->label('Diperbarui')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),
                        ])
                        ->columns(1)
                        ->collapsed(),
                ])
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}