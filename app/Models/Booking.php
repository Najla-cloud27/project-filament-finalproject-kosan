<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_code',
        'duration_in_months',
        'selesai_booking',
        'planned_check_in_date',
        'status',
    ];

    protected $casts = [
        'selesai_booking' => 'datetime',
        'planned_check_in_date' => 'datetime',
    ];

    // Status constants
    const STATUS_PEMBAYARAN_TERTUNDA = 'pembayaran_tertunda';
    const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    // Generate unique booking code
    public static function generateBookingCode()
    {
        do {
            $code = 'BK-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('booking_code', $code)->exists());
        
        return $code;
    }
}
