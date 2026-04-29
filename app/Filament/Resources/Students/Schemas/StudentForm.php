<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->required()
                    ->label('Student Name')
                    ->relationship('user', 'name'),
                Select::make('classroom_id')
                    ->required()
                    ->label('Class')
                    ->relationship('classroom', 'name'),
                TextInput::make('nisn')
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->validationMessages(['unique' => 'the nisn has already been register'])
                    ->label('NISN'),
                TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->label('Phone Number'),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required()
                    ->label('Gender'),
                    Textarea::make('address')
                    ->columnSpanFull()
                    ->default(null)
                    ->label('Address'),
                FileUpload::make('profile_picture')
                    ->default(null)
                    ->label('Profile Picture')
                    ->directory('Siswa')
                    ->disk('public'),
            ]);
    }
}
