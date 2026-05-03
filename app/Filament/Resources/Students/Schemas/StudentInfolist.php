<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Foto Profil')
                        ->icon('heroicon-o-camera')
                        ->schema([
                            ImageEntry::make('profile_picture')
                                ->hiddenLabel()
                                ->label('')
                                ->disk('public')
                                ->imageHeight(220)
                                ->imageSize(300)
                                ->alignCenter()
                                ->placeholder('-'),
                        ]),

                    Section::make('Status & Waktu')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Terdaftar')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),

                            TextEntry::make('updated_at')
                                ->label('Diperbarui')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),
                        ])
                        ->columns(1)
                        ->collapsed(),
                ])
                ->columnSpan(1),

                Section::make('Informasi Siswa')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Lengkap')
                            ->size('lg')
                            ->weight('medium')
                            ->icon('heroicon-o-user-circle')
                            ->columnSpanFull(),

                        TextEntry::make('nisn')
                            ->label('NISN')
                            ->icon('heroicon-o-identification')
                            ->copyable()
                            ->copyMessage('NISN disalin!'),

                        TextEntry::make('classroom.name')
                            ->label('Kelas')
                            ->icon('heroicon-o-building-office-2')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('gender')
                            ->label('Jenis Kelamin')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'male', 'Laki-laki'   => 'info',
                                'female', 'Perempuan' => 'pink',
                                default               => 'gray',
                            }),

                        TextEntry::make('phone_number')
                            ->label('No. HP')
                            ->icon('heroicon-o-phone')
                            ->copyable()
                            ->copyMessage('Nomor disalin!'),

                        TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable()
                            ->copyMessage('Email disalin!'),

                        TextEntry::make('address')
                            ->label('Alamat')
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('Belum diisi.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
            ])
            ->columns(3);
    }
}