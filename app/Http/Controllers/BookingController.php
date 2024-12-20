<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Screening;
use App\Models\Film;
use App\Models\User;
use App\Models\Seat;
use Illuminate\Support\Facades\Log;




class BookingController extends Controller
{
    // public function store(Request $request) {
    //     $screening = Screening::find($request->screening_id);

    //     if ($screening->available_seats < $request->seats_booked) {
    //         return response()->json(['error' => 'Not enough seats available'], 400);
    //     }

    //     $booking = Booking::create($request->all());
    //     $screening->available_seats -= $request->seats_booked;
    //     $screening->save();

    //     return response()->json($booking);
    // }
    public function index()
    {
        // Mengambil data booking beserta user dan screening yang terkait
        $bookings = Booking::with(['user', 'screening.film'])->get();
        return view('bookings.index', compact('bookings'));
    }

    // Menampilkan form untuk membuat booking baru
    public function create()
    {

        $users = User::where('role', 'user')->get();
        $screenings = Screening::all();
        $screeningSeats = [];

        foreach ($screenings as $screening) {
            $screeningSeats[$screening->id] = $screening->seats; // Sesuaikan dengan hubungan seats di model Screening
        }

        return view('bookings.create', compact('screenings', 'users', 'screeningSeats'));
    }


    public function store(Request $request) {
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
            'user_id' => 'required|exists:users,id',
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
            'user_id' => $validated['user_id'],
            'screening_id' => $validated['screening_id'],
            'seats_booked' => count($seats), // Total number of seats booked
        ]);

        // Log sukses booking
        Log::info('Booking successful:', ['booking_id' => $booking->id]);

        return redirect()->route('bookings.index')->with('success', 'Booking successful.');
    }



    // Menampilkan form untuk mengedit booking
    public function edit($id)
    {
        $booking = Booking::with('seats')->findOrFail($id);
        $users = User::all();
        $screenings = Screening::all();

        // Ambil data kursi yang tersedia untuk screening yang dipilih
        $screeningSeats = [];
        foreach ($screenings as $screening) {
            $seats = Seat::where('screening_id', $screening->id)->get();
            $screeningSeats[$screening->id] = $seats;
        }

        return view('bookings.edit', compact('booking', 'users', 'screenings', 'screeningSeats'));
    }

    // Update booking film
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string',
        ]);

        // Temukan booking yang ingin diupdate
        $booking = Booking::findOrFail($id);

        // Batalkan kursi yang sudah dibooking sebelumnya
        foreach ($booking->seats as $seat) {
            $seat->update(['is_booked' => false]);
        }

        // Update data booking
        $booking->update([
            'user_id' => $validated['user_id'],
            'screening_id' => $validated['screening_id'],
        ]);

        // Ambil kursi baru yang dipilih
        $seats = Seat::whereIn('seat_number', $validated['seats'])
                    ->where('screening_id', $validated['screening_id'])
                    ->where('is_booked', '0')
                    ->get();

        // Tandai kursi yang baru sebagai dibooking
        foreach ($seats as $seat) {
            $seat->update(['is_booked' => '1']);
            $booking->seats()->syncWithoutDetaching([$seat->id]); // Menyimpan kursi yang dibooking
        }

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }
    // Menghapus booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Batalkan semua kursi yang dibooking
        foreach ($booking->seats as $seat) {
            $seat->update(['is_booked' => false]);
        }

        // Hapus booking
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
