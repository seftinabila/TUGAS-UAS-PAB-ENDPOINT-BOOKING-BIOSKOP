<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Screening;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiFilmController extends Controller
{
    // API untuk mendapatkan jadwal film berdasarkan ID film
    public function getFilms()
    {
        // Ambil semua film yang ada di database
        $films = Film::all();

        // Ambil jadwal screening yang akan datang
        $schedules = Screening::join('films', 'screenings.film_id', '=', 'films.id')
            ->where('screenings.show_time', '>=', Carbon::now())
            ->orderBy('screenings.show_time', 'asc')
            ->select('films.title as film_title', 'screenings.id', 'screenings.show_time', 'screenings.available_seats', 'screenings.film_id') // Menambahkan 'film_id'
            ->get();

        // Buat array untuk menyimpan data film beserta jadwalnya
        $filmData = $films->map(function($film) use ($schedules) {
            // Ambil jadwal film yang terkait dengan film ini
            $filmSchedules = $schedules->where('film_id', $film->id);

            // Format data film dan jadwalnya
            return [
                'film_title' => $film->title,
                'schedules' => $filmSchedules,
            ];
        });

        return response()->json($filmData);
    }


    public function getSchedules($filmId)
    {
        // Pastikan film valid
        $film = Film::findOrFail($filmId);

        // Ambil jadwal screening yang akan datang
        $schedules = Screening::where('film_id', $filmId)
                              ->where('show_time', '>=', Carbon::now())
                              ->select('available_seats','show_time')
                              ->orderBy('show_time', 'asc')
                              ->get(['id', 'show_time']);

        return response()->json([
            'film_title' => $film->title,
            'schedules' => $schedules,
        ]);
    }
}
