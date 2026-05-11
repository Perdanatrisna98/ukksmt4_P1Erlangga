<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Transaksi Peminjaman')
                        ->icon('heroicon-o-ticket')
                        // ->description('Pilih peminjam, aset, dan tentukan batas waktu pengembalian.')
                        ->schema([
                            Select::make('user_id')
                                ->label('Peminjam')
                                ->relationship(
                                    'user',
                                    'name',
                                    fn ($query) => $query->role('peminjam')
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false),

                            Select::make('asset_id')
                                ->label('Aset')
                                ->relationship('asset', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false),

                            DatePicker::make('due_at')
                                ->label('Batas Kembali')
                                ->required()
                                ->native(false)
                                ->minDate(now()->addDay())
                                ->displayFormat('d M Y'),

                            Textarea::make('note')
                                ->label('Catatan Tambahan')
                                ->placeholder('Tulis catatan jika ada keperluan khusus…')
                                ->rows(3)
                                ->maxLength(500)
                                ->columnSpanFull(),
                        ])
                        ->columns(3),
                ])
                ->columnSpanFull(),
            ])
            ->columns(3);
    }
}