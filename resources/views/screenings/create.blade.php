@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Jadwal Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Tambah Jadwal</h5>
        </div>
        <div class="card-body">

            <a href="{{ route('screenings.index') }}" class="btn btn-secondary mb-3">Kembali</a>

            <form action="{{ route('screenings.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="film_id" class="form-label">Pilih Film</label>
                    <select name="film_id" id="film_id" class="form-select" required>
                        <option value="">-- Pilih Film --</option>
                        @foreach($films as $film)
                            <option value="{{ $film->id }}">{{ $film->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="show_time" class="form-label">Waktu Tayang</label>
                    <input type="datetime-local" name="show_time" id="show_time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="available_seats" class="form-label">Jumlah Kursi Tersedia</label>
                    <input type="number" name="available_seats" id="available_seats" class="form-control" required min="1">
                </div>

                <!-- Dynamic Seat Selection -->
                <div id="seat-selection" class="mb-3">
                    <!-- Kursi akan ditambahkan di sini secara dinamis -->
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('available_seats').addEventListener('input', function() {
        var seatCount = this.value;
        var seatSelectionDiv = document.getElementById('seat-selection');
        seatSelectionDiv.innerHTML = ''; // Clear previous seat inputs

        for (var i = 1; i <= seatCount; i++) {
            var seatInput = document.createElement('input');
            seatInput.type = 'text';
            seatInput.name = 'seats[' + i + '][seat_number]';
            seatInput.placeholder = 'Nomor Kursi ' + i;
            seatInput.className = 'form-control mb-2';
            seatSelectionDiv.appendChild(seatInput);
        }
    });
</script>
@endsection
