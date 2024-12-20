<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use App\Models\Film;
use App\Models\Seat; // Tambahkan Seat Model


class ScreeningController extends Controller
{
    // public function index() {
    //     $screenings = Screening::with('film')->get();
    //     return response()->json($screenings);
    // }
    public function index()
    {
        $screenings = Screening::with('film')->get();
        return view('screenings.index', compact('screenings'));
    }

    // Menampilkan form untuk membuat screening baru
    public function create()
    {
        $films = Film::all();
        return view('screenings.create', compact('films'));
    }

    // Menyimpan screening baru dan menambahkan kursi yang tersedia
    public function store(Request $request)
{
    $validated = $request->validate([
        'film_id' => 'required|exists:films,id',
        'show_time' => 'required|date',
        'available_seats' => 'required|integer|min:1',
    ]);

    // Buat screening baru
    $screening = Screening::create($validated);

    // Tentukan jumlah kursi per baris
    $seatsPerRow = 10;  // Misalnya ada 10 kursi per baris

    // Hitung jumlah baris berdasarkan kursi yang tersedia
    $totalRows = ceil($validated['available_seats'] / $seatsPerRow);

    // Buat kursi dengan format "A1", "A2", ..., "B1", "B2", ...
    for ($row = 0; $row < $totalRows; $row++) {
        $rowLetter = chr(65 + $row); // 'A' adalah ASCII 65, 'B' adalah 66, dan seterusnya

        // Hitung jumlah kursi di baris ini (tidak lebih dari 10 kursi per baris)
        $seatsInThisRow = min($seatsPerRow, $validated['available_seats'] - ($row * $seatsPerRow));

        for ($i = 1; $i <= $seatsInThisRow; $i++) {
            $seatNumber = $rowLetter . $i;  // Format kursi seperti "A1", "A2", ...

            // Buat kursi baru untuk screening
            Seat::create([
                'screening_id' => $screening->id,
                'seat_number' => $seatNumber,
                'is_booked' => false,  // Kursi belum dibooking
            ]);
        }
    }

    return redirect()->route('screenings.index')->with('success', 'Screening created successfully.');
}


    // Menampilkan form untuk mengedit screening
    public function edit(Screening $screening)
    {
        $films = Film::all();
        return view('screenings.edit', compact('screening', 'films'));
    }

    // Mengupdate screening dan jumlah kursi yang tersedia
    public function update(Request $request, Screening $screening)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'show_time' => 'required|date',
            'available_seats' => 'required|integer|min:1',
        ]);

        // Update screening
        $screening->update($validated);

        // Cek jika jumlah kursi berubah, tambahkan atau hapus kursi
        $currentSeats = $screening->seats()->count();

        if ($validated['available_seats'] > $currentSeats) {
            // Tambahkan kursi jika jumlah available_seats lebih besar
            for ($i = $currentSeats + 1; $i <= $validated['available_seats']; $i++) {
                Seat::create([
                    'screening_id' => $screening->id,
                    'seat_number' => $i,
                    'is_booked' => false,  // Kursi baru belum dibooking
                ]);
            }
        } elseif ($validated['available_seats'] < $currentSeats) {
            // Hapus kursi jika available_seats berkurang
            $seatsToDelete = $currentSeats - $validated['available_seats'];
            $seatsToRemove = $screening->seats()->orderBy('seat_number', 'desc')->take($seatsToDelete)->get();

            foreach ($seatsToRemove as $seat) {
                $seat->delete();
            }
        }

        return redirect()->route('screenings.index')->with('success', 'Screening updated successfully.');
    }

    // Menghapus screening dan kursi yang terkait
    public function destroy(Screening $screening)
    {
        // Hapus semua kursi yang terkait dengan screening ini
        $screening->seats()->delete();

        // Hapus screening
        $screening->delete();

        return redirect()->route('screenings.index')->with('success', 'Screening deleted successfully.');
    }
}
