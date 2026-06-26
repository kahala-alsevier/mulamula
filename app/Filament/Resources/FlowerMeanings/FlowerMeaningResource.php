<?php

namespace App\Filament\Resources\FlowerMeanings;

use App\Filament\Resources\FlowerMeanings\Pages\ManageFlowerMeanings;
use App\Models\FlowerMeaning;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FlowerMeaningResource extends Resource
{
    protected static ?string $model = FlowerMeaning::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'flower_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('flower_name')->label('Nama Jenis Bunga')->required(),
                TextInput::make('symbolism')->label('Arti Singkat / Makna')->required(),

                FileUpload::make('image')
                    ->label('Gambar Filosofi Bunga')
                    ->image()
                    ->directory('filosofi-bunga')
                    ->nullable(),

                Textarea::make('description')->label('Uraian Filosofi Lengkap')->required()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('flower_name')
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->square(),

                TextColumn::make('flower_name')->label('Nama Bunga')->searchable(),
                TextColumn::make('symbolism')->label('Makna Filosofis'),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFlowerMeanings::route('/'),
        ];
    }
}
