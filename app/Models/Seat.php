<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'screening_id',
        'seat_number',
        'is_booked'
    ];
    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seat')->withTimestamps(); // Relasi dengan tabel pivot
    }
}
