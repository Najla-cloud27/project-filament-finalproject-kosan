<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh booking 1
        Booking::create([
            'room_id' => 1,
            'user_id' => 2,
            'status' => 'dikonfirmasi',
            'total' => 500000, // total biaya booking
            'check_in' => now(),
            'check_out' => now()->addDays(3),
            'booking_code' => 'BOOK-' . mt_rand(1000, 9999),
        ]);

        // Contoh booking 2
        Booking::create([
            'room_id' => 2,
            'user_id' => 2,
            'status' => 'dikonfirmasi',
            'total' => 750000,
            'check_in' => now()->addDays(1),
            'check_out' => now()->addDays(4),
            'booking_code' => 'BOOK-' . mt_rand(1000, 9999),
        ]);

        // Contoh booking 3
        Booking::create([
            'room_id' => 1,
            'user_id' => 3,
            'status' => 'pendikonfirmasi',
            'total' => 400000,
            'check_in' => now()->addDays(2),
            'check_out' => now()->addDays(5),
            'booking_code' => 'BOOK-' . mt_rand(1000, 9999),
        ]);
    }
}
