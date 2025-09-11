<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'room_id',
        'phone',
        'band_name',
        'members',
        'additional_request',
        'total_price',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    // Relationship: Booking belongs to a Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relationship: Booking belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instruments()
    {
        return $this->belongsToMany(Instrument::class, 'booking_instrument')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // Scope: Query bookings for a specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
