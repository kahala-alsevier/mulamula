<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Models\Transaction;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'in' => 'Uang Masuk (Penjualan Manual)',
                        'out' => 'Uang Keluar (Operasional/Stok)',
                    ])->required()->live()->default('in'),

                TextInput::make('total_amount')
                    ->label('Total Nominal (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->readOnly(fn (Get $get) => $get('type') === 'in'),

                Textarea::make('notes')->label('Catatan')->columnSpanFull(),

                Repeater::make('items')
                    ->label('Pilih Produk (Hanya Uang Masuk)')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->options(Product::pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('price', Product::find($state)?->price ?? 0)),
                        TextInput::make('quantity')->label('Jumlah Qty')->numeric()->default(1)->required()->live(),
                        TextInput::make('price')->label('Harga Satuan')->numeric()->prefix('Rp')->required()->readOnly(),
                    ])
                    ->columns(3)
                    ->live()
                    ->hidden(fn (Get $get) => $get('type') === 'out')
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $items = $get('items') ?? [];
                        $total = 0;
                        foreach ($items as $item) {
                            $total += ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                        }
                        $set('total_amount', $total);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice_number')
            ->columns([
                TextColumn::make('invoice_number')->label('Nomor Faktur')->searchable(),
                
                TextColumn::make('user.name')
                    ->label('Nama Pelanggan')
                    ->default('Transaksi Langsung Toko (Offline)')
                    ->searchable(),

                TextColumn::make('type')->label('Jenis')
                    ->badge()->color(fn (string $state): string => $state === 'in' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state) => $state === 'in' ? 'Masuk' : 'Keluar'),
                
                TextColumn::make('source')->label('Asal Transaksi')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'manual' ? 'gray' : 'info')
                    ->formatStateUsing(fn (string $state) => $state === 'manual' ? 'Pencatatan Admin' : 'Pesanan Web (Online)'),
                
                TextColumn::make('total_amount')
                    ->label('Total Pembayaran')
                    ->money('IDR'),
                
                TextColumn::make('status')->label('Status Transaksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'completed' => 'Sukses Terbayar',
                        'pending' => 'Menunggu Pembayaran',
                        'failed' => 'Gagal',
                        default => 'Batal'
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Tanggal Pembuatan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
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
            'index' => ManageTransactions::route('/'),
        ];
    }
}