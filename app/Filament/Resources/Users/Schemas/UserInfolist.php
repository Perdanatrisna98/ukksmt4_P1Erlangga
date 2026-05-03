<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Akun')
                        ->icon('heroicon-o-user')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nama Lengkap')
                                ->size('lg')
                                ->weight('medium')
                                ->columnSpanFull(),

                            TextEntry::make('email')
                                ->label('Email')
                                ->icon('heroicon-o-envelope')
                                ->copyable()
                                ->copyMessage('Email disalin!'),

                            TextEntry::make('roles.name')
                                ->label('Role')
                                ->badge()
                                ->color(fn ($state) => match ($state) {
                                    'admin', 'super_admin' => 'danger',
                                    'peminjam'             => 'info',
                                    default                => 'gray',
                                }),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Status Verifikasi')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            IconEntry::make('email_verified_at')
                                ->label('Email Terverifikasi')
                                ->boolean()
                                ->getStateUsing(fn ($record) => ! is_null($record->email_verified_at))
                                ->trueIcon('heroicon-o-check-badge')
                                ->falseIcon('heroicon-o-x-circle')
                                ->trueColor('success')
                                ->falseColor('danger'),

                            TextEntry::make('email_verified_at')
                                ->label('Tanggal Verifikasi')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('Belum diverifikasi'),
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