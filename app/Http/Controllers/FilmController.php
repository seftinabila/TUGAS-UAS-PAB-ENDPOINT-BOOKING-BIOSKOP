<?php

namespace App\Http\Controllers;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    // public function index() {
    //     $films = Film::all();
    //     return response()->json($films);
    // }
    // public function store(Request $request) {
    //     $film = Film::create($request->all());
    //     return response()->json($film);
    // }

    public function index() {
        $films = Film::all();
        // return $films;
        return view('films.index', compact('films'));
    }

    public function create() {
        return view('films.create');
    }

    public function store(Request $request) {
        // Validasi input form
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'genre' => 'required|string|max:100',
            'release_date' => 'required|date',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        // Menangani file poster
        if ($request->hasFile('poster')) {
            // Menyimpan file poster ke folder public/posters
            $posterPath = $request->file('poster')->store('posters', 'public');
        } else {
            $posterPath = null; // Jika tidak ada poster, set ke null
        }

        // Membuat film baru dengan data yang sudah tervalidasi
        Film::create([
            'title' => $validated['title'],
            'genre' => $validated['genre'],
            'release_date' => $validated['release_date'],
            'description' => $validated['description'],
            'poster' => $posterPath, // Menyimpan path poster
        ]);

        return redirect()->route('films.index')->with('success', 'Film created successfully.');
    }

    public function edit(Film $film) {
        return view('films.edit', compact('film'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'genre' => 'required|string|max:100',
        'release_date' => 'required|date',
        'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi poster
    ]);

    // Mencari film yang akan diupdate
    $film = Film::findOrFail($id);

    // Menangani upload file poster jika ada
    if ($request->hasFile('poster')) {
        // Menghapus poster lama jika ada


        // Menyimpan file poster baru
        $posterPath = $request->file('poster')->store('posters', 'public');
    } else {
        // Jika tidak ada file baru, menggunakan poster lama
        $posterPath = $film->poster;
    }

    // Update data film
    $film->update([
        'title' => $validated['title'],
        'genre' => $validated['genre'],
        'release_date' => $validated['release_date'],
        'description' => $validated['description'],
        'poster' => $posterPath, // Menyimpan path poster
    ]);

    return redirect()->route('films.index')->with('success', 'Film updated successfully.');
}


    public function destroy(Film $film) {
        $film->delete();
        return redirect()->route('films.index')->with('success', 'Film deleted successfully.');
    }

}
