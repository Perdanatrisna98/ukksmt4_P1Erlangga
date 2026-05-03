<?php

namespace App\Filament\Resources\Majors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MajorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Jurusan')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nama Jurusan')
                                ->size('lg')
                                ->weight('medium')
                                ->columnSpanFull(),

                            TextEntry::make('code')
                                ->label('Kode Jurusan')
                                ->badge(),

                            TextEntry::make('classrooms_count')
                                ->label('Jumlah Kelas')
                                ->getStateUsing(fn ($record) => $record->classes()->count())
                                ->suffix(' kelas')
                                ->badge()
                                ->color('gray'),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Status')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            IconEntry::make('is_active')
                                ->label('Status Jurusan')
                                ->boolean()
                                ->trueIcon('heroicon-o-check-circle')
                                ->falseIcon('heroicon-o-x-circle')
                                ->trueColor('success')
                                ->falseColor('danger'),
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