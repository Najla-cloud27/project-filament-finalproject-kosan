<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'size',
        'status',   
        'fasilitas',
        'stok',
        'main_image_url',
        'icon_svg',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stok' => 'integer',
    ];

    // Accessor untuk fasilitas agar selalu return array
    public function getFasilitasAttribute()
    {
        // Get raw value from attributes
        $value = $this->attributes['fasilitas'] ?? null;
        
        if (is_null($value) || $value === '') {
            return [];
        }
        
        // If already array, return it
        if (is_array($value)) {
            return $value;
        }
        
        // Decode JSON string - might be double-encoded
        $decoded = json_decode($value, true);
        
        // If still string after first decode, decode again (double-encoded JSON)
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }
        
        // Return decoded value or empty array
        return is_array($decoded) ? $decoded : [];
    }

    // Mutator untuk fasilitas agar selalu save sebagai JSON
    public function setFasilitasAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['fasilitas'] = json_encode($value);
        } elseif (is_string($value)) {
            // Check if already JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['fasilitas'] = $value;
            } else {
                $this->attributes['fasilitas'] = json_encode([$value]);
            }
        } else {
            $this->attributes['fasilitas'] = json_encode([]);
        }
    }

    // Status constants
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_TERISI = 'terisi';
    const STATUS_PEMELIHARAAN = 'pemeliharaan';
    const STATUS_SUDAH_DIPESAN = 'sudah_dipesan';

    // Automatically generate slug from name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($room) {
            if (empty($room->slug)) {
                $room->slug = Str::slug($room->name);
            }
        });
                
        static::updating(function ($room) {
            if ($room->isDirty('name')) {
                $room->slug = Str::slug($room->name);
            }
        });

    }

    // Relations
    public function images()
    {
        return $this->hasMany(RoomImage::class, 'room_id_room');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Helper methods
    public function isTersedia()
    {
        return $this->status === 'tersedia';
    }

    public function isBooked()
    {
        return $this->status === 'sudah_dipesan' || $this->status === 'terisi';
    }
}
