<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as WidgetDasar;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use Carbon\Carbon;

class StatistikToko extends WidgetDasar
{
    // Menghubungkan komponen statistik dengan penyaring halaman dasbor
    use \Filament\Widgets\Concerns\InteractsWithPageFilters;

    protected function getStats(): array
    {
        // Mengambil pilihan dari formulir penyaring waktu (bawaan pilihan jika kosong adalah bulan ini)
        $pilihanWaktu = $this->pageFilters['periode'] ?? 'bulan_ini';

        // Menyiapkan kueri dasar untuk transaksi yang berhasil/sukses
        $kueriMasuk = Transaction::where('status', 'completed')->where('type', 'in');
        $kueriKeluar = Transaction::where('status', 'completed')->where('type', 'out');

        // Menerapkan penyaring tanggal berdasarkan pilihan pengguna
        if ($pilihanWaktu === 'hari_ini') {
            $kueriMasuk->whereDate('created_at', Carbon::today());
            $kueriKeluar->whereDate('created_at', Carbon::today());
        } elseif ($pilihanWaktu === 'minggu_ini') {
            $kueriMasuk->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $kueriKeluar->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($pilihanWaktu === 'bulan_ini') {
            $kueriMasuk->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
            $kueriKeluar->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        } elseif ($pilihanWaktu === 'tahun_ini') {
            $kueriMasuk->whereYear('created_at', Carbon::now()->year);
            $kueriKeluar->whereYear('created_at', Carbon::now()->year);
        }

        // Menghitung total omset dan pengeluaran operasional
        $totalOmset = (clone $kueriMasuk)->sum('total_amount');
        $totalPengeluaran = (clone $kueriKeluar)->sum('total_amount');

        // Memisahkan sumber omset antara Midtrans (situs web) dan Input Manual Admin
        $omsetMidtrans = (clone $kueriMasuk)->where('source', 'online')->sum('total_amount');
        $omsetManual = (clone $kueriMasuk)->where('source', 'manual')->sum('total_amount');

        return [
            Stat::make('Total Omset Toko', 'Rp ' . number_format($totalOmset, 0, ',', '.'))
                ->description('Seluruh pendapatan dari transaksi sukses')
                ->color('success'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description('Biaya pembelian stok dan kebutuhan operasional')
                ->color('danger'),

            Stat::make('Perbandingan Pendapatan Jalur Penjualan', 'Situs Pembayaran vs Input Manual')
                ->description('Situs Web (Midtrans): Rp ' . number_format($omsetMidtrans, 0, ',', '.') . ' | Admin Manual: Rp ' . number_format($omsetManual, 0, ',', '.'))
                ->color('info'),
        ];
    }
}