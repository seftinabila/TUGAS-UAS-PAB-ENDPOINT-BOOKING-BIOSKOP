<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBookingController extends Controller
{
    // API untuk membuat booking baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seats' => 'required|array|min:1', // Pastikan array
        ]);

        $seats = $validated['seats'];
        $screeningId = $validated['screening_id'];

        // Validasi kursi yang dipilih
        foreach ($seats as $seatNumber) {
            $seat = Seat::where('screening_id', $screeningId)
                        ->where('seat_number', $seatNumber)
                        ->first();

            if (!$seat || $seat->is_booked) {
                return response()->json(['error' => "Kursi {$seatNumber} sudah di booking."], 400);
            }
        }

        // Tandai kursi sebagai dibooking
        foreach ($seats as $seatNumber) {
            Seat::where('screening_id', $screeningId)
                ->where('seat_number', $seatNumber)
                ->update(['is_booked' => 1]);
        }

        // Simpan booking
        $booking = Booking::create([
            'user_id' => Auth::user()->id,
            'screening_id' => $screeningId,
            'seats_booked' => count($seats),
        ]);

        return response()->json([
            'message' => 'Booking successful',
            'booking' => $booking,
        ]);
    }

    // API untuk melihat semua booking
    public function index()
    {
        $bookings = Booking::with('user', 'screening.film')->get();
        return response()->json($bookings);
    }
}
