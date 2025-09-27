<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'capacity',
        'room_type_id',
        'instruments',
        'price',
        'status',
        'available_times'
    ];

    protected $casts = [
        'abailable_times' => 'array',
        'price' => 'decimal:2',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Query rooms with optional filters.
     * Example: Room::queryRooms(['room_type' => 'A'])
     */
    public static function queryRooms($filters = [])
    {
        $query = self::query();
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }
        return $query->get();
    }
}
