<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'genre',
        'release_date',
        'poster',
    ];

    /**
     * Get the screenings for the film.
     */
    public function screenings()
    {
        return $this->hasMany(Screening::class, 'film_id');
    }
}
