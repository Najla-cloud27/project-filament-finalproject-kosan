<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Carbon\Carbon;

class UserWeeklyChart extends ChartWidget
{
    // ini buat header
    protected static ?string $heading = 'User Mingguan';

    // ini buat column
    protected int|string|array $columnSpan = 12;

    // ini buat data 
    protected function getData(): array
    {
        $labels = [];
        $data = [];

        // untuk pakai bahasa indonesia
        Carbon::setlocale('id');

        // For Perulangan untuk 7 Hari
        // mengeluarkan datang menggunakan  looping/foreach
        for ($i = 6; $i >= 0; $i--){
            
            // Menghitung tanggal hari ini dikurangi $i hari
            // Ambil tanggal hari ini, terus mundur sehari
            // $date ini untuk memngambil data yang hri ini sma hri yang belkangnya
            $date = Carbon::today()->subDays($i);

            // Tambahkan Label hari dlama foemat singkat (sen,selas,rabu)
            $labels[] = $date->locale('id')->translatedFormat('D');

            // Hitung Jumlah User yang terdaftar pada tanggal tersbeut
            // kayka cariuser yang tanggal daftarnya sama kayak $date,lalu dihitung jumlahnya
            // created_at ini terantung orng yang mendaftar di akun tersebut.
            $data[] = User::whereDate('created_at', $date)->count();
        }
        return [
           'datasets' => [
                [
                    'label' => 'User Registrasi',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 6,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getHeight(): ?int
    {
        return 600;
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,

            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ]
        ];
    }
}
