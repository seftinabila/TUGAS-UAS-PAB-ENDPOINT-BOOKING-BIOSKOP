
@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Edit Jadwal Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Edit Jadwal Film</h5>
        </div>
        <div class="card-body">
            <div class="container">
                <a href="{{ route('screenings.index') }}" class="btn btn-secondary mb-3">Kembali</a>

                <form action="{{ route('screenings.update', $screening->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="film_id" class="form-label">Pilih Film</label>
                        <select name="film_id" id="film_id" class="form-select" required>
                            <option value="">-- Pilih Film --</option>
                            @foreach($films as $film)
                                <option value="{{ $film->id }}" {{ $film->id == $screening->film_id ? 'selected' : '' }}>
                                    {{ $film->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="show_time" class="form-label">Waktu Tayang</label>
                        <input type="datetime-local" name="show_time" id="show_time" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($screening->show_time)) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="available_seats" class="form-label">Jumlah Kursi Tersedia</label>
                        <input type="number" name="available_seats" id="available_seats" class="form-control" value="{{ $screening->available_seats }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
