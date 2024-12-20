<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama - Pemesanan Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Hero Section */

        .hero {
            background: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://source.unsplash.com/1600x900/?cinema,movies') no-repeat center center;
            background-size: cover;
            height: 80vh;
            color: #0b0c29;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        .hero p {
            font-size: 1.5rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
        }

        .btn-light {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            color: #007bff;
            font-weight: bold;
            padding: 10px 20px;
            text-transform: uppercase;
            font-size: 1.1rem;
            border-radius: 30px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-light:hover {
            background-color: #ffffff;
            color: #0056b3;
            transform: scale(1.05);
        }

        /* Featured Movies Section */
        .movies .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
        }

        .movies .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
            color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Booking Section */
        .booking form {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .booking form:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .form-select {
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 1.1rem;
            box-shadow: none;
            transition: border-color 0.3s ease;
        }

        .form-select:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 25px;
            padding: 12px 30px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        /* Footer Styling */
        footer {
            background-color: #343a40;
            color: white;
            font-size: 1rem;
            padding: 20px 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 3rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .movies .card {
                margin-bottom: 20px;
            }

            .movies .card-img-top {
                height: 200px;
            }

            .btn-light {
                font-size: 1rem;
                padding: 8px 16px;
            }

            .booking form {
                padding: 20px;
            }

            .form-select,
            .btn-success {
                font-size: 1rem;
            }
        }
    </style>


</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Pemesan Tiket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/movies">Film</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/bookings">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero bg-primary text-white text-center py-5" style="background-image: url('{{ asset('storage/posters/uDgMubG0EGqYscDapO0l9FKEM56xo9iV5NmX1AjL.png') }}'); background-size: cover; background-position: center;">
        <div class="container">
            <h1 class="display-4">Selamat Datang di Pemesanan Tiket Cinema</h1>
            <p class="lead">Nikmati pengalaman menonton film favorit Anda di bioskop terbaik. Pesan tiket sekarang!</p>
            <a href="/bookings" class="btn btn-light btn-lg">Pesan Tiket Sekarang</a>
        </div>
    </section>

    <!-- Featured Movies Section -->
    <section class="movies py-5">
        <div class="container">
            <h2 class="text-center mb-4">Film Terbaru</h2>
            <div class="row">
                @foreach ($films as $film)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $film->poster) }}" class="card-img-top" alt="{{ $film->title }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $film->title }}</h5>
                            <p class="card-text">{{ Str::limit($film->description, 100) }}</p>
                            <a href="/films/{{ $film->id }}" class="btn btn-primary">Lihat Detail </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section class="booking py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Pilih Jadwal Screening</h2>
            <form action="/bookings" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="movie" class="form-label">Pilih Film</label>
                        <select class="form-select" id="movie" name="movie_id" required>
                            @foreach ($films as $film)
                            <option value="{{ $film->id }}">{{ $film->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="screening" class="form-label">Pilih Jadwal</label>
                        <select class="form-select" id="screening" name="screening_id" required>
                            @foreach ($screenings as $screening)
                            <option value="{{ $screening->id }}">{{ $screening->start_time }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Pesan Tiket</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 Pemesanan Tiket Cinema. Semua hak dilindungi.</p>
    </footer>

    <!-- Optional JavaScript -->
    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
