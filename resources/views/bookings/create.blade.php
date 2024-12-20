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

            <form action="{{ route('bookings.store') }}" method="POST" onsubmit="return validateForm()">
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
                    <label for="screening_id" class="form-label">Jadwal</label>
                    <select name="screening_id" class="form-control" id="screening_id" required>
                        <option value="">-Pilih Jadwal & Judul Film-</option>
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
                    <strong>Kursi Tersedia: <span id="available-seats-count">0</span></strong>
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


<!-- JavaScript untuk Pilihan Kursi -->
<script>
    document.getElementById('screening_id').addEventListener('change', function () {
        var screeningId = this.value;
        var availableSeats = @json($screeningSeats); // Data kursi yang tersedia
        var seatSelectionDiv = document.getElementById('seat-selection');
        seatSelectionDiv.innerHTML = ''; // Bersihkan daftar kursi
        var availableSeatsCount = 0; // Hitung kursi tersedia

        if (availableSeats && availableSeats[screeningId]) {
            // Tentukan jumlah total kursi dan baris
            let totalSeats = availableSeats[screeningId].length;
            let seatsPerRow = 10; // Kursi per baris
            let rows = Math.ceil(totalSeats / seatsPerRow); // Hitung jumlah baris

            // Kelompokkan kursi ke dalam baris
            let seatsByRow = Array.from({ length: rows }, (_, rowIndex) => {
                return availableSeats[screeningId]
                    .slice(rowIndex * seatsPerRow, (rowIndex + 1) * seatsPerRow)
                    .map((seat, seatIndex) => {
                        let rowLetter = String.fromCharCode(65 + rowIndex); // Huruf baris (A, B, C, ...)
                        let seatNumber = `${rowLetter}${seatIndex + 1}`; // Gabungkan huruf dan angka
                        seat.seat_number = seatNumber; // Perbarui nomor kursi
                        if (!seat.is_booked) availableSeatsCount++;
                        return seat;
                    });
            });

            // Render kursi berdasarkan baris
            seatsByRow.forEach((rowSeats, rowIndex) => {
                let rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';
                rowDiv.textContent = `Kursi ${String.fromCharCode(65 + rowIndex)}: `; // Tambahkan label baris

                rowSeats.forEach((seat) => {
                    let seatButton = document.createElement('button');
                    seatButton.type = 'button';
                    seatButton.className = 'btn btn-outline-primary m-1';
                    seatButton.textContent = seat.seat_number;
                    seatButton.setAttribute('data-seat-number', seat.seat_number);

                    // Tandai kursi yang sudah dibooking
                    if (seat.is_booked) {
                        seatButton.classList.add('disabled');
                        seatButton.disabled = true;
                    } else {
                        seatButton.onclick = function () {
                            // Toggle status pemilihan kursi
                            seatButton.classList.toggle('selected');
                            updateSelectedSeats();
                        };
                    }

                    rowDiv.appendChild(seatButton);
                });

                seatSelectionDiv.appendChild(rowDiv); // Tambahkan baris kursi ke layout
            });
        } else {
            seatSelectionDiv.innerHTML = '<p class="text-danger">Tidak ada kursi tersedia untuk screening ini.</p>';
        }

        // Perbarui jumlah kursi tersedia
        document.getElementById('available-seats-count').textContent = availableSeatsCount;
    });

    // Fungsi untuk memperbarui kursi yang dipilih tetap sama
    var maxSeats = 5; // Maksimal kursi yang bisa dipilih
    function updateSelectedSeats() {
        var selectedSeats = [];
        var selectedButtons = document.querySelectorAll('#seat-selection button.selected');

        if (selectedButtons.length > maxSeats) { // Batasi maksimal 5 kursi
            alert('Anda hanya dapat memilih hingga ' + maxSeats + ' kursi.');
            selectedButtons[selectedButtons.length - 1].classList.remove('selected');
            return;
        }

        selectedButtons.forEach(function (button) {
            selectedSeats.push(button.textContent);
        });

        document.getElementById('selected-seats').value = JSON.stringify(selectedSeats);
        document.getElementById('seats_booked').value = selectedSeats.length;
    }

    // Validasi sebelum submit
    function validateForm() {
        var selectedSeats = document.getElementById('selected-seats').value;
        if (!selectedSeats || selectedSeats === '[]') {
            alert('Pilih setidaknya satu kursi sebelum melanjutkan.');
            return false;
        }
        return true;
    }

</script>
@endsection
