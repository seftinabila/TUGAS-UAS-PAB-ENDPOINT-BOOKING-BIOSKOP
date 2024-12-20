@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Tambah Film</h5>
        </div>
        <div class="card-body">

            <a href="{{ route('films.index') }}" class="btn btn-secondary mb-3">Kembali</a>

            <form action="{{ route('films.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Film</label>
                    <input type="text" name="title" class="form-control" id="title" required>
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <select name="genre" id="" class="form-control" id="genre" required>
                        <option value="Horror">Horror</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Drama">Drama</option>
                        <option value="Action">Action</option>
                        <option value="Animation">Animation</option>
                        <option value="Documenter">Documenter</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Thriller">Thriller</option>
                    </select>

                </div>
                <div class="mb-3">
                    <label for="release_date" class="form-label">Tanggal Rilis</label>
                    <input type="date" name="release_date" class="form-control" id="release_date" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                </div>
                <!-- Add file input for poster -->
                <div class="mb-3">
                    <label for="poster" class="form-label">Poster Film</label>
                    <input type="file" name="poster" class="form-control" id="poster" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
