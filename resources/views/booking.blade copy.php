
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking Film</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('front/css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/font-awesome.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/elegant-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/plyr.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/nice-select.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/slicknav.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('front/css/style.css')}}" type="text/css">

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="./index.html">
                            <img src="img/logo.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li class="active"><a href="./index.html">Homepage</a></li>
                                <li><a href="./categories.html">Categories <span class="arrow_carrot-down"></span></a>
                                    <ul class="dropdown">
                                        <li><a href="./categories.html">Categories</a></li>
                                        <li><a href="./anime-details.html">Anime Details</a></li>
                                        <li><a href="./anime-watching.html">Anime Watching</a></li>
                                        <li><a href="./blog-details.html">Blog Details</a></li>
                                        <li><a href="./signup.html">Sign Up</a></li>
                                        <li><a href="./login.html">Login</a></li>
                                    </ul>
                                </li>
                                {{-- <li><a href="./blog.html">Our Blog</a></li>
                                <li><a href="#">Contacts</a></li> --}}
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="header__right">
                        <a href="#" class="search-switch"><span class="icon_search"></span></a>
                        <a href="{{route('login')}}"><span class="icon_profile"></span></a>
                    </div>
                </div>
            </div>
            <div id="mobile-menu-wrap"></div>
        </div>
    </header>
    <!-- Header End -->

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

    <section class="anime-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                <form action="{{ route('bookings-tiket.store') }}" method="POST">
                    @csrf <!-- CSRF token for form security -->

                    <!-- Pilih Kursi -->
                    @foreach($screenings as $screening)
                        <div class="anime__details__episodes">
                            <div class="section-title">
                                <h5>{{ $screening->film->title }} ({{ $screening->show_time }})</h5>
                            </div>
                            <div class="seat-container">
                                @foreach($screeningSeats[$screening->id] as $seat)
                                    <button type="button" class="btn btn-outline-primary m-1 seat-button" data-seat-id="{{ $seat->id }}" {{ $seat->is_booked ? 'disabled' : '' }}>
                                        {{ $seat->seat_number }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Hidden input untuk screening_id -->
                    <input type="hidden" name="screening_id" id="screening-id" value="">

                    <!-- Hidden input untuk menyimpan kursi yang dipilih -->
                    <input type="hidden" name="selected_seats" id="selected-seats" value="">

                    <br><br>
                    <!-- Tombol Booking -->
                    <button type="submit" class="btn btn-primary" id="book-btn">Booking</button>
                </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Anime Section End -->
<!-- Product Section End -->

<!-- Footer Section Begin -->
<footer class="footer">
    <div class="page-up">
        <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer__logo">
                    <a href="./index.html"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="footer__nav">
                    <ul>
                        <li class="active"><a href="./index.html">Homepage</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank"></a>
                  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>

              </div>
          </div>
      </div>
  </footer>
  <!-- Footer Section End -->

  <!-- Search model Begin -->

<!-- Search model end -->

<!-- Js Plugins -->
{{-- <script src="{{asset('front/js/jquery-3.3.1.min.js')}}"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="{{asset('front/js/bootstrap.min.js')}}"></script>
<script src="{{asset('front/js/player.js')}}"></script>
<script src="{{asset('front/js/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('front/js/mixitup.min.js')}}"></script>
<script src="{{asset('front/js/jquery.slicknav.js')}}"></script>
<script src="{{asset('front/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('front/js/main.js')}}"></script>
<script>
    let selectedSeats = [];
    const maxSeats = 5; // Batas maksimal pemilihan kursi

    // Menambahkan event klik pada tombol kursi
    document.querySelectorAll('.seat-button').forEach(button => {
        button.addEventListener('click', function() {
            let seatId = this.getAttribute('data-seat-id');

            if (this.classList.contains('selected')) {
                // Jika kursi sudah dipilih, batalkan pemilihannya
                selectedSeats = selectedSeats.filter(id => id !== seatId);
                this.classList.remove('selected');
            } else {
                // Jika kursi belum dipilih, dan batas pemilihan kursi belum tercapai
                if (selectedSeats.length < maxSeats) {
                    selectedSeats.push(seatId);
                    this.classList.add('selected');
                } else {
                    alert('Anda hanya dapat memilih maksimal ' + maxSeats + ' kursi.');
                }
            }

            // Update hidden input dengan ID kursi yang dipilih
            document.getElementById('selected-seats').value = JSON.stringify(selectedSeats);
        });
    });

    // Menentukan screening_id yang dipilih
    document.querySelectorAll('.seat-container').forEach(container => {
        container.addEventListener('click', function () {
            let screeningId = container.closest('.anime__details__episodes').querySelector('input[name="screening_id"]');
            document.getElementById('screening-id').value = screeningId.value;
        });
    });
</script>


</body>

</html>
