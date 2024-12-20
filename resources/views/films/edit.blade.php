@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Edit Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Edit Film</h5>
        </div>
        <div class="card-body">
            <div class="container">
                <a href="{{ route('films.index') }}" class="btn btn-secondary mb-3">Kembali</a>

                <form action="{{ route('films.update', $film->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Film</label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $film->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" name="genre" class="form-control" id="genre" value="{{ old('genre', $film->genre) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Tanggal Rilis</label>
                        <input type="date" name="release_date" class="form-control" id="release_date" value="{{ old('release_date', $film->release_date) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" id="description" rows="4" required>{{ old('description', $film->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="poster" class="form-label">Poster Film</label>
                        <!-- Display the current poster if available -->
                        @if ($film->poster)
                            <div>
                                <img src="{{ asset('storage/' . $film->poster) }}" alt="Poster Film" width="150">
                            </div>
                        @endif
                        <input type="file" name="poster" class="form-control" id="poster" accept="image/*">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti poster.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
