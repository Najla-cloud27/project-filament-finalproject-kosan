<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Kosan',
            'email' => 'admin@kosan.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
            'nik' => '3201111111111111',
            'role' => 'pemilik',
        ]);

        User::create([
            'name' => 'Penyewa Kosan',
            'email' => 'penyewa@kosan.com',
            'password' => Hash::make('password'),
            'phone_number' => '089876543210',
            'nik' => '3201222222222222',
            'role' => 'penyewa',
        ]);
    }
}
