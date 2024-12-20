@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Booking Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Tambah Booking Film</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary mb-3">Kembali</a>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Pengguna</label>
                    <select name="user_id" class="form-control" id="user_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="screening_id" class="form-label">Screening</label>
                    <select name="screening_id" class="form-control" id="screening_id" required>
                        @foreach($screenings as $screening)
                            <option value="{{ $screening->id }}">{{ $screening->film->title }} ({{ $screening->show_time }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="seats_booked" class="form-label">Jumlah Kursi</label>
                    <input type="number" name="seats_booked" class="form-control" id="seats_booked" min="1" required readonly>
                </div>

                <div class="mb-3">
                    <label for="seats" class="form-label">Pilih Kursi</label>
                    <div id="seat-selection">
                        <!-- Daftar kursi yang tersedia akan ditampilkan di sini -->
                    </div>
                    <input type="hidden" name="seats[]" id="selected-seats" value="">
                </div>

                <button type="submit" class="btn btn-primary">Book</button>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk memilih kursi dan mengisi input hidden
    document.getElementById('screening_id').addEventListener('change', function() {
        var screeningId = this.value;
        var availableSeats = @json($screeningSeats); // Data kursi yang tersedia
        var seatSelectionDiv = document.getElementById('seat-selection');
        seatSelectionDiv.innerHTML = ''; // Bersihkan daftar kursi

        // Pastikan screeningId valid dan memiliki data kursi
        if (availableSeats && availableSeats[screeningId]) {
            availableSeats[screeningId].forEach(function(seat) {
                var seatButton = document.createElement('button');
                seatButton.type = 'button';
                seatButton.className = 'btn btn-outline-primary m-1';
                seatButton.textContent = seat.seat_number;
                seatButton.setAttribute('data-seat-number', seat.seat_number); // Menambahkan data-seat-number untuk referensi

                // Jika kursi sudah dibooking, tambahkan kelas disabled
                if (seat.is_booked) {
                    seatButton.classList.add('disabled');
                    seatButton.disabled = true;
                } else {
                    seatButton.onclick = function() {
                        // Toggle status pemilihan kursi
                        seatButton.classList.toggle('selected');
                        updateSelectedSeats(); // Update kursi yang dipilih
                    };
                }

                seatSelectionDiv.appendChild(seatButton);
            });
        }
    });

    // Fungsi untuk memperbarui kursi yang dipilih dan jumlah kursi
    function updateSelectedSeats() {
        var selectedSeats = [];
        var selectedButtons = document.querySelectorAll('#seat-selection button.selected');
        selectedButtons.forEach(function(button) {
            selectedSeats.push(button.textContent); // Ambil nomor kursi
        });
        console.log(selectedSeats); // Verifikasi array kursi yang dipilih

        // Perbarui nilai pada input hidden dan input jumlah kursi
        document.getElementById('selected-seats').value = selectedSeats.join(',');
        document.getElementById('seats_booked').value = selectedSeats.length; // Update jumlah kursi
    }
</script>


@endsection
