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
        $user = User::find(Auth::user()->id);

        // Get today's date
        $today = Carbon::today();

        // Get screenings for the selected film that are scheduled today
        $screenings = Screening::where('film_id', $filmId)
                                ->whereDate('show_time', $today) // Filter screenings by today's date
                                ->orderBy('show_time', 'asc')
                                ->get();

        // Get seats for each screening
        $screeningSeats = [];
        foreach ($screenings as $screening) {
            $seats = Seat::where('screening_id', $screening->id)->get();
            $screeningSeats[$screening->id] = $seats;
        }

        // Get the selected film details
        $films = Film::find($filmId);

        // Return the view with the data
        return view('booking', compact('user', 'screenings', 'screeningSeats', 'filmId', 'films'));
    }



    // Menyimpan pemesanan baru
    public function store(Request $request)
    {
        // Validasi bahwa selected_seats ada dan valid JSON
        $request->validate([
            'selected_seats' => 'required|json',
        ]);

        // Mengambil kursi yang dipilih dari form
        $selectedSeats = json_decode($request->selected_seats);

        // Memeriksa jika kursi yang dipilih sudah dibooking
        $seats = Seat::whereIn('id', $selectedSeats)->get();
        foreach ($seats as $seat) {
            if ($seat->is_booked) {
                return back()->with('error', 'Salah satu atau lebih kursi yang dipilih sudah dibooking.');
            }
        }

        // Menyimpan data booking
        $booking = Booking::create([
            'user_id' => Auth::id(),  // ID pengguna yang sedang login
            'screening_id' => $request->screening_id,  // ID screening
            'selected_seats' => json_encode($selectedSeats), // ID kursi yang dipilih
        ]);

        // Menandai kursi yang sudah dibooking
        foreach ($seats as $seat) {
            $seat->is_booked = true;
            $seat->save();
        }

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil!');
    }

}
