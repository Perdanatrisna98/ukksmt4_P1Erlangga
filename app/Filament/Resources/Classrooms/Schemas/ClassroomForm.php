<?php

namespace App\Filament\Resources\Classrooms\Schemas;

use App\Models\Major;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassroomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Kelas')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            Select::make('major_id')
                                ->label('Jurusan')
                                ->options(
                                    Major::where('is_active', true)->pluck('name', 'id')
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false),

                            Select::make('level')
                                ->label('Tingkat')
                                ->options([
                                    10 => 'Kelas X',
                                    11 => 'Kelas XI',
                                    12 => 'Kelas XII',
                                ])
                                ->required()
                                ->native(false),

                            TextInput::make('name')
                                ->label('Nama Kelas')
                                ->required()
                                ->placeholder('TKJ 1')
                                ->maxLength(50)
                                ->columnSpanFull(),
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