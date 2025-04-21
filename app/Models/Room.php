<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function bookings()
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class);
    }

}
