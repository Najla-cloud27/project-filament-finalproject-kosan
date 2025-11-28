<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\RevenueChart;

class Dashboard extends Page
{
    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $navigationGroup = 'Admin';

    protected function getWidgets(): array
    {
        return [
            RevenueChart::class,
            // nanti bisa tambah widget lain: RoomStatusChart, 
            // BookingTrendChart, RecentBookings, dan lain-lain
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

}
