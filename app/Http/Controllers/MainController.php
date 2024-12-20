<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Screening;
use App\Models\User;
use App\Models\Seat;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class MainController extends Controller
{
    // Menampilkan halaman utama dengan film terbaru dan screening yang tersedia
    public function index()
    {
        // Ambil daftar film yang tersedia
        $films = Film::all();

        // Ambil jadwal screening yang tersedia
        $screenings = Screening::all();

        // Kirim data film dan screening ke view
        return view('welcome', compact('films', 'screenings'));
    }

    public function create($filmId)
    {

        $films = Film::find($filmId);
        // Get today's date
        $today = Carbon::today();

        // Get screenings for the selected film that are scheduled today
        $screenings = Screening::where('film_id', $filmId)
                                ->whereDate('show_time', $today) // Filter screenings by today's date
                                ->orderBy('show_time', 'asc')
                                ->get();

        $screeningSeats = [];

        foreach ($screenings as $screening) {
            $screeningSeats[$screening->id] = $screening->seats; // Sesuaikan dengan hubungan seats di model Screening
        }

        return view('booking', compact('screenings', 'screeningSeats','films'));
    }



    // Menyimpan pemesanan baru
    public function store(Request $request)
{
    // Menambahkan log untuk mencatat data request yang diterima
    $decodedSeats = $request->input('seats');
    if (is_array($decodedSeats) && count($decodedSeats) === 1 && is_string($decodedSeats[0])) {
        $decodedSeats = json_decode($decodedSeats[0], true);
        $request->merge(['seats' => $decodedSeats]);
    }

    // Log untuk memastikan data sudah benar
    Log::info('Decoded seats:', $request->input('seats'));

    // Validasi data
    $validated = $request->validate([
        'screening_id' => 'required|exists:screenings,id',
        'user_id' => 'required',
        'seats' => 'required|array|min:1', // Pastikan ini adalah array
    ]);

    $seats = $request->input('seats');
    $screeningId = $validated['screening_id'];

    // Validasi manual untuk memastikan kursi terkait screening dan belum dibooking
    foreach ($seats as $seatNumber) {
        $seat = Seat::where('screening_id', $screeningId)
                    ->where('seat_number', $seatNumber)
                    ->first();

        if (!$seat) {
            return redirect()->back()->withErrors(['error' => "Seat {$seatNumber} is invalid for this screening."]);
        }

        if ($seat->is_booked) {
            return redirect()->back()->withErrors(['error' => "Seat {$seatNumber} is already booked."]);
        }
    }

    // Tandai kursi sebagai dibooking
    foreach ($seats as $seatNumber) {
        Seat::where('screening_id', $screeningId)
            ->where('seat_number', $seatNumber)
            ->update(['is_booked' => '1']);
    }

    // Simpan booking baru
    $booking = Booking::create([
        'user_id' => Auth::user()->id,
        'screening_id' => $validated['screening_id'],
        'seats_booked' => count($seats), // Total number of seats booked
    ]);

    // Log sukses booking
    Log::info('Booking successful:', ['booking_id' => $booking->id]);

    // Redirect ke halaman pembayaran
    return redirect()->route('checkout', ['bookingId' => $booking->id]);
}


}
