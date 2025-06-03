<?php

namespace App\Filament\Widgets;

use App\Models\SensorReading;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LatestSensorReadingsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '5s';
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $latestReading = SensorReading::latest()->first();

        if (!$latestReading) {
            return [
                Stat::make('Status', 'Belum ada data sensor')
                    ->description('Pastikan ESP32 mengirim data')
                    ->descriptionIcon('heroicon-m-exclamation-triangle') // Mini icon
                    ->color('danger'),
            ];
        }

        $airQualityStrings = [0 => 'Udara Segar', 1 => 'Polusi Rendah', 2 => 'Polusi Tinggi', 3 => 'Peringatan/Error'];
        $airQualityDesc = $airQualityStrings[$latestReading->air_quality_level] ?? 'Tidak Diketahui';
        $airQualityColor = match ($latestReading->air_quality_level) {
            0 => 'success', 1 => 'warning', 2 => 'danger', 3 => 'danger', default => 'gray',
        };

        return [
            Stat::make('CO2 PPM', number_format($latestReading->co2_ppm, 1) . ' ppm')
                ->description('Kualitas Udara: ' . $airQualityDesc)
                ->descriptionIcon($latestReading->air_quality_level == 0 ? 'heroicon-m-sparkles' : 'heroicon-m-cloud-arrow-down') // Mini icons
                ->color($airQualityColor)
                ->chart($this->getRecentCO2Data()),

            Stat::make('Suhu', number_format($latestReading->temperature_c, 1) . ' °C')
                ->description('Sensor DHT22')
                // --- PERBAIKAN DI SINI ---
                // ->descriptionIcon('heroicon-m-thermometer') // BARIS LAMA
                ->descriptionIcon('heroicon-o-sun') // BARIS BARU: Ganti dengan ikon outline 'sun' sebagai alternatif, atau 'fire' untuk panas
                // Atau coba 'heroicon-o-cpu-chip' jika merepresentasikan sensor elektronik
                // Sayangnya, heroicons v2 tidak punya 'thermometer' di set mini.
                // Untuk versi outline (o-) atau solid (s-), 'thermometer' ada:
                // ->descriptionIcon('heroicon-o-thermometer') // Jika Anda lebih suka versi outline
                // ->descriptionIcon('heroicon-s-thermometer') // Jika Anda lebih suka versi solid
                // --- AKHIR PERBAIKAN ---
                ->color('primary')
                ->chart($this->getRecentTempData()),

            Stat::make('Kelembaban', number_format($latestReading->humidity_percent, 1) . ' %')
                ->description('Sensor DHT22')
                ->descriptionIcon('heroicon-m-cloud') // Mini icon
                ->color('info')
                ->chart($this->getRecentHumidityData()),
        ];
    }

    // ... (method getRecentCO2Data, getRecentTempData, getRecentHumidityData tetap sama) ...
    protected function getRecentCO2Data(): array
    {
        return SensorReading::latest()->take(15)->get()->pluck('co2_ppm')->reverse()->values()->all();
    }
    protected function getRecentTempData(): array
    {
        return SensorReading::latest()->take(15)->get()->pluck('temperature_c')->reverse()->values()->all();
    }
    protected function getRecentHumidityData(): array
    {
        return SensorReading::latest()->take(15)->get()->pluck('humidity_percent')->reverse()->values()->all();
    }
}
