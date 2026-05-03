<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Akun')
                        ->icon('heroicon-o-user')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->placeholder('Ahyeon')
                                ->maxLength(100)
                                ->columnSpanFull(),

                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Email ini sudah digunakan.',
                                ])
                                ->placeholder('contoh@email.com'),

                            Select::make('roles')
                                ->label('Role')
                                ->relationship('roles', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Keamanan')
                        ->icon('heroicon-o-lock-closed')
                        ->schema([
                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->revealable()
                                ->required(fn ($operation) => $operation === 'create')
                                ->dehydrateStateUsing(
                                    fn ($state) => filled($state)
                                        ? bcrypt($state)
                                        : null
                                )
                                ->dehydrated(fn ($state) => filled($state))
                                ->helperText('Kosongkan jika tidak ingin mengubah password.')
                                ->minLength(8),
                        ]),

                    Section::make('Verifikasi')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            DateTimePicker::make('email_verified_at')
                                ->label('Tanggal Verifikasi Email')
                                ->placeholder('Belum diverifikasi')
                                ->helperText('Isi untuk menandai email sebagai terverifikasi.')
                                ->native(false),
                        ]),
                ])
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}