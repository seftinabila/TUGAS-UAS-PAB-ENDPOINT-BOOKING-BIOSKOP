<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'status',
        'payment_type',
        'payment_reference',
        'snap_token'
    ];

    /**
     * Relasi dengan Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

