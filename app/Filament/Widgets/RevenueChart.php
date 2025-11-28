<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Booking;

class RevenueChart extends LineChartWidget
{
    protected static string $view = 'filament.widgets.revenue-chart';

    protected function getData(): array
    {
        // Ambil semua booking yang sudah dibayar
        $bookings = Booking::query()
            ->where('status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->pluck('revenue','month')
            ->toArray();

        return [
            'labels' => array_keys($bookings), // sumbu X (bulan)
            'datasets' => [
                [
                    'label' => 'Pendapatan Bulanan',
                    'data' => array_values($bookings), // sumbu Y (pendapatan)
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)', // biru elegan
                    'borderColor' => 'rgba(37, 99, 235, 1)',
                ],
            ],
        ];
    }
}
