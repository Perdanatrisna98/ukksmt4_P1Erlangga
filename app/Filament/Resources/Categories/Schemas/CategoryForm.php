<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Detail Kategori')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Kategori')
                                ->required()
                                ->placeholder('Laptop')
                                ->maxLength(100)
                                ->columnSpanFull(),

                            Textarea::make('description')
                                ->label('Deskripsi')
                                ->placeholder('Tulis deskripsi singkat tentang kategori ini..')
                                ->rows(4)
                                ->maxLength(500)
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Gambar')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('image')
                                ->hiddenLabel()
                                ->label('')
                                ->disk('public')
                                ->directory('categories')
                                ->image()
                                ->imageEditor()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Max 2MB — JPG, PNG, or WebP')
                                ->columnSpanFull(),
                        ]),

                        
                ])
                ->columnSpan(1),
                
                Toggle::make('is_active')
                    ->label('Status')
                    ->required()
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true)
                    ->inline(false),
                ])
            ->columns(3);
    }
}