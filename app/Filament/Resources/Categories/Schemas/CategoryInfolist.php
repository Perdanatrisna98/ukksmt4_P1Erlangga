<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Detail Kategori')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nama Kategori')
                                ->size('lg')
                                ->weight('medium'),

                            TextEntry::make('assets_count')
                                ->label('Total Alat')
                                ->getStateUsing(fn ($record) => $record->assets()->count())
                                ->suffix(' alat')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('description')
                                ->label('Deskripsi')
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Gambar')
                        ->icon('heroicon-o-photo')
                        ->hiddenLabel()
                        ->schema([
                            ImageEntry::make('image')
                                ->label('')
                                ->disk('public')
                                ->height(200)
                                ->extraImgAttributes([
                                    'style' => 'width:100%; object-fit:cover; border-radius:8px;',
                                ])
                                ->placeholder('Tidak ada gambar.')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Status & Timestamps')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            IconEntry::make('is_active')
                                ->label('Status')
                                ->boolean()
                                ->trueIcon('heroicon-o-check-circle')
                                ->falseIcon('heroicon-o-x-circle')
                                ->trueColor('success')
                                ->falseColor('danger'),

                            TextEntry::make('created_at')
                                ->label('Created at')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),

                            TextEntry::make('updated_at')
                                ->label('Updated at')
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