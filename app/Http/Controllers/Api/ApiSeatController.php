<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ApiSeatController extends Controller
{
    // API untuk cek ketersediaan kursi berdasarkan screening
    public function checkAvailability($screeningId)
    {
        // Pastikan screening valid
        $screening = Screening::findOrFail($screeningId);

        // Ambil kursi yang belum dibooking
        // $availableSeats = Seat::where('screening_id', $screeningId)
        //                       ->where('is_booked', 0)
        //                       ->pluck('seat_number');
                              $availableSeats = Screening::join('films', 'screenings.film_id', '=', 'films.id')
                              ->leftJoin('seats', 'seats.screening_id', '=', 'screenings.id')
                              ->where('screenings.show_time', '>=', now())
                              ->where('seats.is_booked', 0)
                              ->select([
                                  'films.title as film_title',
                                  'screenings.id as screening_id',
                                  'screenings.show_time',
                                  'screenings.available_seats',
                                  DB::raw('GROUP_CONCAT(seats.seat_number ORDER BY seats.seat_number ASC) as available_seat_numbers'),
                              ])
                              ->groupBy('screenings.id', 'films.title', 'screenings.show_time', 'screenings.available_seats')
                              ->orderBy('screenings.show_time', 'asc')
                              ->get();
        return response()->json([
            'screening_id' => $screeningId,
            'available_seats' => $availableSeats,
        ]);
    }
}
