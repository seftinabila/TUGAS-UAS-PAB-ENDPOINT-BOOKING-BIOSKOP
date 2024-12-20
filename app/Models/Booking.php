<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'screening_id',
        'seats_booked',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the screening that the booking is for.
     */
    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seat', 'booking_id', 'seat_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
