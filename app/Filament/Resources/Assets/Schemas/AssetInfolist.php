<?php

namespace App\Filament\Resources\Assets\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Detail Alat')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            ImageEntry::make('image')
                                ->disk('public')
                                ->imageSize(200)
                                ->hiddenLabel(),

                            TextEntry::make('name')
                                ->label('Nama Alat')
                                ->size('lg')
                                ->weight('medium'),

                            TextEntry::make('category.name')  // Fix: bukan category_id
                                ->label('Kategori')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('code')
                                ->label('Kode')
                                ->fontFamily('mono'),

                            TextEntry::make('description')
                                ->label('Deskripsi')
                                ->html()
                                ->columnSpanFull()
                                ->placeholder('-'),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Kondisi Alat')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            TextEntry::make('good_qty')
                                ->label('Bagus')
                                ->numeric()
                                ->badge()
                                ->color('success'),

                            TextEntry::make('damaged_qty')
                                ->label('Rusak')
                                ->numeric()
                                ->badge()
                                ->color('warning'),

                            TextEntry::make('borrowed_qty')
                                ->label('Dipinjam')
                                ->numeric()
                                ->badge()
                                ->color('info'),

                            TextEntry::make('lost_qty')
                                ->label('Hilang')
                                ->numeric()
                                ->badge()
                                ->color('danger'),

                            TextEntry::make('available_qty')
                                ->label('Tersedia')
                                ->getStateUsing(fn ($record): int => max(0, $record->good_qty - $record->borrowed_qty))
                                ->numeric()
                                ->badge()
                                ->color(fn ($state): string => match (true) {
                                    $state > 5 => 'success',
                                    $state > 0 => 'warning',
                                    default    => 'danger',
                                })
                                ->helperText('Bagus − Dipinjam'),

                            TextEntry::make('total_qty')
                                ->label('Total')
                                ->numeric(),

                            IconEntry::make('is_available')
                                ->label('Status')
                                ->boolean()
                                ->trueIcon('heroicon-o-check-circle')
                                ->falseIcon('heroicon-o-x-circle')
                                ->trueColor('success')
                                ->falseColor('danger')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Timestamps')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Created at')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),

                            TextEntry::make('updated_at')
                                ->label('Updated at')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),
                        ])
                        ->columns(2)
                        ->collapsed(),   // default collapsed karena jarang dilihat
                ])
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}