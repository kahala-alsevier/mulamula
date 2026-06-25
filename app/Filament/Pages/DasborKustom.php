<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as DasborAsli;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DasborKustom extends DasborAsli
{
    // Mengaktifkan fitur formulir penyaring di atas dasbor
    use HasFiltersForm;

    protected static ?string $title = 'Dasbor Pemantau';

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('periode')
                    ->label('Saring Waktu Transaksi')
                    ->options([
                        'hari_ini' => 'Hari Ini',
                        'minggu_ini' => 'Minggu Ini',
                        'bulan_ini' => 'Bulan Ini',
                        'tahun_ini' => 'Tahun Ini',
                    ])
                    ->default('bulan_ini')
                    ->live(),
            ]);
    }
}