<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Models\AssetFine;
use App\Models\AssetReturn;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

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
                        'booked' => 'Dipesan',
                        'borrowed' => 'Dipinjam',
                        'verifying' => 'Dikembalikan',
                        'returned' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'booked' => 'info',
                        'borrowed' => 'warning',
                        'verifying' => 'warning',
                        'returned' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'booked' => 'heroicon-o-clock',
                        'borrowed' => 'heroicon-o-arrow-up-tray',
                        'verifying' => 'heroicon-o-magnifying-glass',
                        'returned' => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
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
                    })
                    ->description(function ($record) {
                        if (! $record->due_at || in_array($record->status, ['booked', 'cancelled'])) {
                            return null;
                        }

                        $due = Carbon::parse($record->due_at)->startOfDay();
                        $now = now()->startOfDay();

                        if ($record->status === 'returned' && $record->returned_at) {
                            $return = Carbon::parse($record->returned_at)->startOfDay();
                            $diff   = $due->diffInDays($return, false);

                            return $diff > 0
                                ? "Terlambat {$diff} hari."
                                : 'Dikembalikan tepat waktu.';
                        }

                        $diff = $now->diffInDays($due, false);

                        return match (true) {
                            $diff < 0  => 'Terlambat ' . abs($diff) . ' hari.',
                            $diff === 0 => 'Jatuh tempo hari ini!',
                            default    => "Sisa {$diff} hari.",
                        };
                    }),

                TextColumn::make('returned_at')
                    ->label('Dikembalikan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                        'status' => 'borrowed',
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
                    ->modalHeading('Konfirmasi Pengembalian?')
                    ->modalDescription('Aset akan dicatat telah dikembalikan dan tiket ditutup.')
                    ->visible(fn ($record) => $record->status === 'verifying')
                    ->schema([
                        Select::make('condition')
                            ->label('Kondisi Alat')
                            ->required()
                            ->options([
                                'good' => 'Bagus',
                                'damaged' => 'Rusak',
                                'lost' => 'Hilang'
                            ])->default('good'),

                        TextArea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $returnTime = now();
                            $qty = $record->qty;
                            $asset = $record->asset;
                            $price = $asset->purchase_price;

                            $record->update([
                                'status' => 'returned',
                                'returned_at' => $returnTime
                            ]);

                            $assetReturn = AssetReturn::create([
                                'ticket_id' => $record->id,
                                'user_id' => $record->user_id,
                                'asset_id' => $record->asset_id,
                                'qty' => $qty,
                                'condition' => $data['condition'],
                                'notes' => $data['notes'] ?? null,
                                'returned_at' => $returnTime,
                            ]);

                            $lateDays = $record->due_at?->startOfDay()->diffInDays($returnTime->startOfDay(), false);

                            if ($lateDays > 0) {
                                
                            AssetFine::create([
                                'asset_return_id' => $assetReturn->id,
                                'type' => 'late',
                                'amount' => ($price * $qty * 0.01) * $lateDays,
                                'notes' => "Denda keterlambatan {$lateDays} hari. Tarif: 1% x harga aset x qty per hari.",
                            ]);
                            }

                            $fineRates = [
                                'damaged' => ['type' => 'damage', 'rate' => 0.30],
                                'lost' => ['type' => 'lost', 'rate' => 1],

                            ];

                            if (isset($fineRates[$data['condition']])) {
                                $fine = $fineRates[$data['condition']];
                                AssetFine::create([
                                    'asset_return_id' => $assetReturn->id,
                                    'type' => $fine['type'],
                                    'amount' => ($price * $qty) * $fine['rate'],
                                    'notes' => "Asset" . ucfirst($data['condition']),
                                ]);
                            }
                        });
                    })
                    ->modalHeading('Asset Return')
                    ->modalSubmitActionLabel('Confirm Return')
                    ->modalWidth('md')
                    ->button(),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->requiresConfirmation(),
                ])
                ->tooltip('Aksi lain')
                ->icon('heroicon-m-ellipsis-vertical'),
            ]);
    }
}