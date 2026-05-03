<?php

namespace App\Filament\Resources\Majors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MajorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Jurusan')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Jurusan')
                                ->required()
                                ->placeholder('Teknik Komputer dan Jaringan')
                                ->maxLength(100)
                                ->columnSpanFull(),

                            TextInput::make('code')
                                ->label('Kode Jurusan')
                                ->required()
                                ->placeholder('TKJ')
                                ->maxLength(10)
                                ->columnSpanFull()
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode jurusan ini sudah digunakan.',
                                ])
                                ->extraInputAttributes([
                                    'style' => 'text-transform: uppercase;',
                                ]),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Toggle::make('is_active')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true)
                    ->inline(false),
            ]);
    }
}