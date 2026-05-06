<?php

namespace App\Filament\Resources\AssetReturns\Schemas;

use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AssetReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Detail Pengembalian')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->description('Pilih tiket yang sedang dalam proses verifikasi pengembalian.')
                        ->schema([
                            Select::make('ticket_id')
                                ->label('Nomor Tiket')  // Fix: typo 'Ricket Number'
                                ->relationship(
                                    'ticket',
                                    'ticket_number',
                                    fn ($query) => $query->where('status', 'verifying')
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false)
                                ->live()
                                ->afterStateUpdated(function ($state, $set) {
                                    $ticket = Ticket::find($state);
                                    $set('asset_id', $ticket?->asset_id);
                                    $set('qty', $ticket?->qty ?? 1);
                                }),

                            Select::make('asset_id')
                                ->label('Aset')
                                ->relationship('asset', 'name')
                                ->disabled()
                                ->dehydrated()
                                ->native(false),

                            TextInput::make('qty')
                                ->label('Jumlah')
                                ->numeric()
                                ->default(1)
                                ->readOnly()
                                ->suffix('unit'),

                            Select::make('condition')
                                ->label('Kondisi Aset')
                                ->options([
                                    'good'    => 'Baik',
                                    'damaged' => 'Rusak',
                                    'lost'    => 'Hilang',
                                ])
                                ->default('good')
                                ->required()
                                ->native(false)
                                ->helperText('Kondisi aset saat dikembalikan.'),

                            Textarea::make('notes')
                                ->label('Catatan')
                                ->placeholder('Tulis catatan kondisi atau keterangan tambahan…')
                                ->rows(3)
                                ->maxLength(500)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Verifikasi')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            Select::make('user_id')
                                ->label('Diverifikasi Oleh')
                                ->relationship('user', 'name')
                                ->default(fn () => Auth::id())  // Fix: Auth::class → Auth::id()
                                ->disabled()
                                ->dehydrated()
                                ->native(false),

                            DateTimePicker::make('returned_at')
                                ->label('Waktu Pengembalian')
                                ->default(Carbon::now())
                                ->disabled()
                                ->dehydrated()
                                ->native(false)
                                ->displayFormat('d M Y, H:i'),
                        ]),
                ])
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}