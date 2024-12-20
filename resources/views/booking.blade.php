
@extends('layouts.front.app')

@section('content')
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="hero__slider owl-carousel">

                <div class="hero__items set-bg" data-setbg="{{ asset('storage/' . $films->poster) }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">{{ $films->genre }}</div>
                                <h2>{{ $films->title }}</h2>
                                <p>{{ Str::limit($films->description, 50) }}</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>


    <!-- Hero Section End -->

    <!-- Product Section Begin -->
     <!-- Anime Section Begin -->
     <section class="anime-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('bookings-tiket.store') }}" method="POST" onsubmit="return validateForm()">
                        @csrf
                    <!-- Pilih Jam Tayang -->
                    <div class="anime__details__episodes">
                        <div class="section-title">
                            <h5>Pilih Jam Tayang</h5>
                        </div>
                        <select name="screening_id" class="form-control" id="screening_id" required>
                            <option value="">-Pilih Jadwal & Judul Film-</option>
                            @foreach($screenings as $screening)
                                <option value="{{ $screening->id }}">{{ $screening->film->title }} ({{ $screening->show_time }})</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Pilih Kursi -->
                    <div class="anime__details__episodes">
                        <div class="section-title">
                            <h5>Pilih Kursi</h5>
                        </div>
                        <div id="seat-selection">
                            <!-- Daftar kursi yang tersedia akan ditampilkan di sini -->
                        </div>
                        <input type="hidden" name="seats[]" id="selected-seats" value="">
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    </div>

                    <!-- Tombol Booking -->
                    <button type="submit" class="btn btn-primary" >Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Anime Section End -->
<!-- Product Section End -->

<!-- Footer Section Begin -->

<!-- Js Plugins -->
{{-- <script src="{{asset('front/js/jquery-3.3.1.min.js')}}"></script> --}}

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
