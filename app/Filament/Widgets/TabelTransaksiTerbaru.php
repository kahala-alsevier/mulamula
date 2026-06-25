<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as WidgetTabel;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Transaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class TabelTransaksiTerbaru extends WidgetTabel
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Daftar Transaksi Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $pilihanWaktu = $this->pageFilters['periode'] ?? 'bulan_ini';

        $kueriData = Transaction::query()->latest();

        if ($pilihanWaktu === 'hari_ini') {
            $kueriData->whereDate('created_at', Carbon::today());
        } elseif ($pilihanWaktu === 'minggu_ini') {
            $kueriData->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($pilihanWaktu === 'bulan_ini') {
            $kueriData->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        } elseif ($pilihanWaktu === 'tahun_ini') {
            $kueriData->whereYear('created_at', Carbon::now()->year);
        }

        return $table
            ->query($kueriData->limit(5))
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Nomor Faktur'),

                TextColumn::make('user.name')
                    ->label('Nama Pelanggan')
                    ->default('Langsung Toko (Offline)'),

                TextColumn::make('type')
                    ->label('Jenis Aliran')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'in' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state) => $state === 'in' ? 'Uang Masuk' : 'Uang Keluar'),

                TextColumn::make('source')
                    ->label('Jalur Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'manual' ? 'gray' : 'info')
                    ->formatStateUsing(fn (string $state) => $state === 'manual' ? 'Input Manual' : 'Situs Web (Midtrans)'),

                TextColumn::make('total_amount')
                    ->label('Total Uang')
                    ->money('IDR'),

                TextColumn::make('created_at')
                    ->label('Waktu Transaksi')
                    ->dateTime(),
            ]);
    }
}
