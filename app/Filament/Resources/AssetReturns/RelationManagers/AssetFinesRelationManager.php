<?php

namespace App\Filament\Resources\AssetReturns\RelationManagers;

use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetFinesRelationManager extends RelationManager
{
    protected static string $relationship  = 'assetFines';
    protected static ?string $title        = 'Denda Aset';
    protected static ?string $recordTitleAttribute = 'type';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Jenis Denda')
                    ->options([
                        'late'   => 'Terlambat',
                        'damage' => 'Rusak',
                        'lost'   => 'Hilang',
                    ])
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $livewire) {
                        $record = $livewire->ownerRecord;
                        $ticket = $record->ticket;

                        if (! $state || ! $ticket) {
                            return;
                        }

                        if ($state === 'lost') {
                            $set('amount', $ticket->asset->purchase_price ?? 0);
                            $set('notes', "Denda kehilangan aset {$ticket->asset->name} (Tiket: {$ticket->ticket_number}).");

                        } elseif ($state === 'late') {
                            if (!$ticket->due_at) {
                                return;
                            }

                            $due      = Carbon::parse($ticket->due_at)->startOfDay();
                            $returned = Carbon::parse($record->returned_at ?? now())->startOfDay();

                            if ($returned->gt($due)) {
                                $days = $due->diffInDays($returned);
                                $set('amount', $days * 10000);
                                $set('notes', "Keterlambatan {$days} hari. Tarif: Rp10.000/hari.");
                            } else {
                                $set('amount', 0);
                                $set('notes', 'Tidak ada keterlambatan. Dikembalikan tepat waktu.');
                            }

                        } elseif ($state === 'damage') {
                            $set('amount', 0);
                            $set('notes', 'Jelaskan detail kerusakan pada catatan.');
                        }
                    }),

                TextInput::make('amount')
                    ->label('Jumlah Denda')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->minValue(0),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->placeholder('Tulis keterangan tambahan mengenai denda ini…')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->label('Jenis Denda')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'late'   => 'Terlambat',
                        'damage' => 'Rusak',
                        'lost'   => 'Hilang',
                        default  => ucfirst($state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'late'   => 'warning',
                        'damage' => 'warning',
                        'lost'   => 'danger',
                        default  => 'gray',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'late'   => 'heroicon-o-clock',
                        'damage' => 'heroicon-o-wrench-screwdriver',
                        'lost'   => 'heroicon-o-exclamation-triangle',
                        default  => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('amount')
                    ->label('Jumlah Denda')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->weight('medium'),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->notes)
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Denda')
                    ->options([
                        'late'   => 'Terlambat',
                        'damage' => 'Rusak',
                        'lost'   => 'Hilang',
                    ])
                    ->native(false),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Denda'),
                AssociateAction::make()
                    ->label('Kaitkan Denda'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DissociateAction::make(),
                    DeleteAction::make()->requiresConfirmation(),
                ])
                ->tooltip('Aksi')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }
}