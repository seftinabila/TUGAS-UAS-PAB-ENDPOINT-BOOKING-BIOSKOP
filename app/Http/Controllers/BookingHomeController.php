<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Screening;
use App\Models\Film;
use App\Models\User;
use App\Models\Seat;
use Illuminate\Support\Facades\Log;


class BookingHomeController extends Controller
{
    public function index()
    {
        // Mengambil data booking beserta user dan screening yang terkait
        $users = User::where('role', 'user')->get();
        $screenings = Screening::all();
        $screeningSeats = [];

        foreach ($screenings as $screening) {
            $screeningSeats[$screening->id] = $screening->seats; // Sesuaikan dengan hubungan seats di model Screening
        }
        $films = Film::all();
        $bookings = Booking::with(['user', 'screening.film'])->get();
        return view('booking', compact('bookings','films','screenings', 'screeningSeats'));
    }
}
