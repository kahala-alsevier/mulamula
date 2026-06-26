<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Manajemen Pengguna';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Select::make('role')
                    ->label('Hak Akses')
                    ->options([
                        'admin' => 'Administrator',
                        'customer' => 'Pelanggan',
                    ])
                    ->required(),

                TextInput::make('whatsapp_number')
                    ->label('Nomor WhatsApp')
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('whatsapp_number')
                    ->label('No. WA')
                    ->default('-'),

                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'customer' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => $state === 'admin' ? 'Admin' : 'Pelanggan'),
            ])
            ->filters([
            ])
            ->recordActions([
                Action::make('changeRole')
                    ->label(fn (User $record) => $record->role === 'admin' ? 'Ubah ke Customer' : 'Ubah ke Admin')
                    ->color(fn (User $record) => $record->role === 'admin' ? 'success' : 'danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Perubahan Hak Akses')
                    ->modalDescription('Apakah Anda yakin ingin mengubah peran pengguna ini? Tindakan ini akan langsung berdampak pada hak akses masuk panel admin.')

                    ->action(function (User $record) {
                        $targetRole = $record->role === 'admin' ? 'customer' : 'admin';
                        $record->update(['role' => $targetRole]);
                    })

                    ->disabled(function (User $record) {
                        if ($record->id === auth()->id()) {
                            return true;
                        }

                        if ($record->role === 'admin') {
                            $totalAdmin = User::where('role', 'admin')->count();
                            if ($totalAdmin <= 1) {
                                return true;
                            }
                        }

                        return false;
                    }),

                EditAction::make(),

                DeleteAction::make()
                    ->disabled(fn (User $record) =>
                        $record->id === auth()->id() ||
                        ($record->role === 'admin' && User::where('role', 'admin')->count() <= 1)
                    ),
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
            'index' => ManageUsers::route('/'),
        ];
    }
}
