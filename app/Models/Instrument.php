<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    protected $fillable = ['name', 'price', 'stock'];

    public function bookings()
    {
        return $this->belongsTomany(Booking::class, 'booking_instument')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    use HasFactory;
}
