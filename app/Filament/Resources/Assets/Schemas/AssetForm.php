<?php

namespace App\Filament\Resources\Assets\Schemas;

use App\Models\Asset;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AssetForm
{
    protected static function recalculateStock(Get $get, Set $set): void
    {
        $good     = (int) $get('good_qty');
        $damaged  = (int) $get('damaged_qty');
        $borrowed = (int) $get('borrowed_qty');
        $lost     = (int) $get('lost_qty');

        $set('available_qty', max(0, $good - $borrowed)); // Fix: cegah nilai negatif
        $set('total_qty', $good + $damaged + $borrowed + $lost);
    }

    protected static function generateCode(Get $get, Set $set): void
    {
        $category = Category::find($get('category_id'));

        if (! $category) {
            return;
        }

        $prefix = strtoupper(Str::substr($category->name, 0, 3));

        $lastCode = Asset::where('code', 'like', $prefix . '%')
            ->orderByDesc('code')
            ->value('code');

        $nextNumber = $lastCode
            ? ((int) substr($lastCode, 3)) + 1
            : 1;

        $set('code', $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Detail Alat')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::generateCode($get, $set)),

                            TextInput::make('code')
                                ->label('Kode')
                                ->readOnly()
                                ->live(),

                            TextInput::make('name')
                                ->label('Nama Alat')
                                ->required()
                                ->placeholder('MacBook Pro 14-inch')
                                ->columnSpanFull(),

                            RichEditor::make('description')
                                ->label('Deskripsi')
                                ->columnSpanFull()
                                ->extraAttributes([
                                    'style' => 'min-height: 250px'
                                ]),

                            FileUpload::make('image')
                                ->label('Gambar Alat')
                                ->disk('public')
                                ->directory('assets')
                                ->image()
                                ->imageEditor()          // crop & rotate bawaan Filament
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Max 2MB — JPG, PNG, or WebP')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Toggle::make('is_available')
                        ->label('Status')
                        ->required()
                        ->onColor('success')
                        ->offColor('danger')
                        ->default(true)
                        ->inline(false),
                ])
                ->columnSpan(2),

                Section::make('Kondisi Alat')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        TextInput::make('good_qty')
                            ->label('Bagus')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefixIcon('heroicon-o-check-circle')
                            ->prefixIconColor('success')
                            ->live(debounce: 300)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::recalculateStock($get, $set)),

                        TextInput::make('damaged_qty')
                            ->label('Rusak')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefixIcon('heroicon-o-wrench-screwdriver')
                            ->prefixIconColor('warning')
                            ->live(debounce: 300)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::recalculateStock($get, $set)),

                        TextInput::make('borrowed_qty')
                            ->label('Dipinjam')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefixIcon('heroicon-o-arrow-up-tray')
                            ->prefixIconColor('info')
                            ->live(debounce: 300)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::recalculateStock($get, $set)),

                        TextInput::make('lost_qty')
                            ->label('Hilang')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefixIcon('heroicon-o-exclamation-triangle')
                            ->prefixIconColor('danger')
                            ->live(debounce: 300)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::recalculateStock($get, $set)),

                        TextInput::make('available_qty')
                            ->label('Tersedia')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->readOnly()
                            ->prefixIcon('heroicon-o-cube')
                            ->helperText('Tersedia = Bagus − Dipinjam'), // Fix: typo "Availbale"

                        TextInput::make('total_qty')
                            ->label('Total')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->readOnly()
                            ->prefixIcon('heroicon-o-calculator'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}