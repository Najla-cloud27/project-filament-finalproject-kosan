<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'bill_code',
        'total_amount',
        'payment_method',
        'payment_gateaway',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    // Generate unique bill code
    public static function generateBillCode()
    {
        do {
            $code = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('bill_code', $code)->exists());
        
        return $code;
    }
}
