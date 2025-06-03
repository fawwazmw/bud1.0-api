<?php

namespace App\Filament\Widgets;

use App\Models\SensorReading;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class CO2TrendChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Tren CO2 PPM'; // Judul bisa disingkat jika perlu
    protected static ?int $sort = 2; // Tampil setelah StatsOverview
    protected static ?string $pollingInterval = '60s';
    public ?string $filter = '1m';
    protected int | string | array $columnSpan = 'full'; // Mengambil lebar penuh, atau sesuaikan
    // Jika Anda punya 2 chart, bisa set 'md' => 6 (jika grid 12 kolom)
    // Atau sesuaikan berdasarkan jumlah kolom default dashboard Filament (biasanya 2 atau 3)

    protected function getFilters(): ?array
    {
        return [
            '1m' => '1 Menit',
            '24h' => '24 Jam',
            '7d' => '7 Hari',
            '30d' => '30 Hari',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $startDate = match ($activeFilter) {
            '7d' => Carbon::now()->subDays(7),
            '30d' => Carbon::now()->subDays(30),
            '24h' => Carbon::now()->subHours(24),
            default => Carbon::now()->subMinutes(1),
        };

        $readings = SensorReading::where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($readings->isEmpty()) {
            return ['datasets' => [['label' => 'CO2 PPM', 'data' => [0]]], 'labels' => [Carbon::now()->format('H:i')]];
        }

        $labels = $readings->map(fn ($reading) => Carbon::parse($reading->created_at)->format('H:i'));
        $data = $readings->pluck('co2_ppm');

        return [
            'datasets' => [
                [
                    'label' => 'CO2 PPM',
                    'data' => $data,
                    'borderColor' => '#FF6384', // Warna pink/merah
                    'backgroundColor' => '#FFB1C1',
                    'tension' => 0.1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
