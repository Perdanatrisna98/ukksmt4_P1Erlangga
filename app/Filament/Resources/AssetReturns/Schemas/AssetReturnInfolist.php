<?php

namespace App\Filament\Resources\AssetReturns\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssetReturnInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Detail Pengembalian')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->schema([
                            TextEntry::make('ticket.ticket_number')
                                ->label('Nomor Tiket')
                                ->fontFamily('mono'),

                            TextEntry::make('returned_at')
                                ->label('Waktu Pengembalian')
                                ->dateTime('d M Y, H:i')
                                ->icon('heroicon-o-clock'),

                            TextEntry::make('asset.name')
                                ->label('Aset')
                                ->icon('heroicon-o-cube'),

                            TextEntry::make('qty')
                                ->label('Jumlah')
                                ->numeric()
                                ->suffix(' unit'),

                            TextEntry::make('condition')
                                ->label('Kondisi Aset')
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

                            TextEntry::make('notes')
                                ->label('Catatan')
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Verifikator')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            TextEntry::make('user.name')
                                ->label('Diverifikasi Oleh')
                                ->icon('heroicon-o-user'),

                            TextEntry::make('ticket.asset.code')
                                ->label('Kode Aset')
                                ->fontFamily('mono'),

                            TextEntry::make('ticket.status')
                                ->label('Status Tiket')
                                ->badge()
                                ->formatStateUsing(fn ($state) => match ($state) {
                                    'booked'    => 'Dipesan',
                                    'borrowed'  => 'Dipinjam',
                                    'verifying' => 'Dikembalikan',
                                    'returned'  => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default     => ucfirst($state),
                                })
                                ->color(fn ($state) => match ($state) {
                                    'booked'    => 'info',
                                    'borrowed'  => 'warning',
                                    'verifying' => 'warning',
                                    'returned'  => 'success',
                                    'cancelled' => 'danger',
                                    default     => 'gray',
                                }),
                        ]),

                    Section::make('Timestamps')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            TextEntry::make('created_at')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),

                            TextEntry::make('updated_at')
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