<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Informasi Siswa')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Select::make('user_id')
                                ->label('Nama Siswa')
                                ->relationship(
                                    'user',
                                    'name',
                                    fn ($query) => $query->role('peminjam')
                                )
                                ->disableOptionWhen(
                                    fn ($value) => Student::where('user_id', $value)->exists()
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nama Lengkap')
                                        ->required(),

                                    TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->required()
                                        ->unique(ignoreRecord: true),

                                    TextInput::make('password')
                                        ->label('Password')
                                        ->password()
                                        ->revealable()
                                        ->required(),

                                    Select::make('roles')
                                        ->label('Role')
                                        ->relationship('roles', 'name')
                                        ->required(),

                                    DateTimePicker::make('email_verified_at')
                                        ->label('Email Verified At')
                                        ->default(now()),
                                ])
                                ->columnSpanFull(),

                            Select::make('classroom_id')
                                ->label('Kelas')
                                ->relationship('classroom', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('nisn')
                                ->label('NISN')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'NISN ini sudah terdaftar.',
                                ])
                                ->maxLength(20),

                            TextInput::make('phone_number')
                                ->label('No. HP')
                                ->tel()
                                ->required()
                                ->prefix('+62')
                                ->placeholder('8xxxxxxxxxx')
                                ->maxLength(15),

                            Select::make('gender')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'male'   => 'Laki-laki',
                                    'female' => 'Perempuan',
                                ])
                                ->required()
                                ->native(false),

                            Textarea::make('address')
                                ->label('Alamat')
                                ->placeholder('Tulis alamat lengkap siswa…')
                                ->rows(3)
                                ->maxLength(300)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Foto Profil')
                        ->icon('heroicon-o-camera')
                        ->schema([
                            FileUpload::make('profile_picture')
                                ->label('')
                                ->hiddenLabel()
                                ->disk('public')
                                ->directory('students')
                                ->image()
                                ->imageEditor()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Maks 2MB — JPG, PNG, atau WebP')
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}