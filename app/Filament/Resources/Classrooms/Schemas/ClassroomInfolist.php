<?php

namespace App\Filament\Resources\Classrooms\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassroomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Kelas')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nama Kelas')
                                ->size('lg')
                                ->weight('medium'),

                            TextEntry::make('major.name')        // Fix: bukan major_id
                                ->label('Jurusan')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('level')
                                ->label('Tingkat')
                                ->formatStateUsing(fn ($state) => match ((int) $state) {
                                    10      => 'Kelas X',
                                    11      => 'Kelas XI',
                                    12      => 'Kelas XII',
                                    default => "Kelas {$state}",
                                })
                                ->badge()
                                ->color(fn ($state) => match ((int) $state) {
                                    10      => 'success',
                                    11      => 'warning',
                                    12      => 'danger',
                                    default => 'gray',
                                }),

                            TextEntry::make('students_count')
                                ->label('Jumlah Siswa')
                                ->getStateUsing(fn ($record) => $record->students()->count())
                                ->suffix(' siswa')
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
                                ->label('Status Kelas')
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