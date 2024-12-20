<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'screenings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'film_id',
        'show_time',
        'available_seats',
    ];

    /**
     * Get the film associated with the screening.
     */
    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
    public function bookedSeats()
    {
        return $this->seats()->where('is_booked', true);
    }

    public function availableSeatsCount()
    {
        return $this->seats()->where('is_booked', false)->count();
    }
    public function getBookedSeatsCountAttribute()
    {
        return $this->seats()->where('is_booked', true)->count();
    }
    public function updateAvailableSeats()
    {
        $this->available_seats = $this->seats()->where('is_booked', false)->count();
        $this->save();
    }
}
