<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomImage extends Model
{
    use HasFactory;

    protected $table = 'room';
    protected $primaryKey = 'id_room';
    public $timestamps = false;

    protected $fillable = [
        'room_id_room',
        'image_url',
    ];

    protected $casts = [
        'created_at_room' => 'datetime',
    ];

    // Relations
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id_room');
    }
}
