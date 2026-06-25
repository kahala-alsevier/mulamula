<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget as WidgetGrafik;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class GrafikTransaksi extends WidgetGrafik
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Grafik Tren Keuangan';

    public function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $pilihanWaktu = $this->pageFilters['periode'] ?? 'bulan_ini';

        $waktuMulai = Carbon::now()->startOfMonth();
        $waktuSelesai = Carbon::now()->endOfMonth();
        $labelGrafik = [];

        if ($pilihanWaktu === 'hari_ini') {
            $waktuMulai = Carbon::today();
            $waktuSelesai = Carbon::tomorrow();
            $labelGrafik = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'];
        } elseif ($pilihanWaktu === 'minggu_ini') {
            $waktuMulai = Carbon::now()->startOfWeek();
            $waktuSelesai = Carbon::now()->endOfWeek();
            $labelGrafik = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        } elseif ($pilihanWaktu === 'bulan_ini') {
            $waktuMulai = Carbon::now()->startOfMonth();
            $waktuSelesai = Carbon::now()->endOfMonth();
            for ($hari = 1; $hari <= $waktuMulai->daysInMonth; $hari++) {
                $labelGrafik[] = "Hari " . $hari;
            }
        } elseif ($pilihanWaktu === 'tahun_ini') {
            $waktuMulai = Carbon::now()->startOfYear();
            $waktuSelesai = Carbon::now()->endOfYear();
            $labelGrafik = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        }

        $semuaTransaksi = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$waktuMulai, $waktuSelesai])
            ->get();

        $kumpulanUangMasuk = array_fill(0, count($labelGrafik), 0);
        $kumpulanUangKeluar = array_fill(0, count($labelGrafik), 0);

        foreach ($semuaTransaksi as $transaksi) {
            $indeksPeta = 0;
            $tanggalDibuat = Carbon::parse($transaksi->created_at);

            if ($pilihanWaktu === 'hari_ini') {
                $indeksPeta = (int) ($tanggalDibuat->hour / 4);
            } elseif ($pilihanWaktu === 'minggu_ini') {
                $indeksPeta = $tanggalDibuat->isoWeekday() - 1;
            } elseif ($pilihanWaktu === 'bulan_ini') {
                $indeksPeta = $tanggalDibuat->day - 1;
            } elseif ($pilihanWaktu === 'tahun_ini') {
                $indeksPeta = $tanggalDibuat->month - 1;
            }

            if (isset($kumpulanUangMasuk[$indeksPeta])) {
                if ($transaksi->type === 'in') {
                    $kumpulanUangMasuk[$indeksPeta] += $transaksi->total_amount;
                } else {
                    $kumpulanUangKeluar[$indeksPeta] += $transaksi->total_amount;
                }
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Omset Pendapatan (Rp)',
                    'data' => $kumpulanUangMasuk,
                    'borderColor' => '#10B981',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Total Pengeluaran Toko (Rp)',
                    'data' => $kumpulanUangKeluar,
                    'borderColor' => '#EF4444',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labelGrafik,
        ];
    }
}
