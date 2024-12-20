<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingSeat extends Pivot
{
    protected $table = 'booking_seat';

    protected $fillable = ['booking_id', 'seat_id', 'created_at']; // Menyesuaikan kolom yang ada pada tabel pivot
}
