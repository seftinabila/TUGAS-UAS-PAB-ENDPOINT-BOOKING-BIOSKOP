@extends('layouts.front.app')

@section('content')
{{-- <div class="container">
    <h2>Checkout Tiket</h2>


    <p><strong>Film:</strong> {{ $booking->screening->film->title }}</p>
    <p><strong>Jumlah Kursi:</strong> {{ $booking->seats_booked }}</p>
    <p><strong>Total Harga:</strong> Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>

    <form action="{{ route('payments.create', $booking->id) }}" method="POST">
        @csrf
        <label for="payment_method">Pilih Metode Pembayaran:</label>
        <select name="payment_method" required>
            <option value="bank_transfer">Transfer Bank</option>
            <option value="credit_card">Kartu Kredit</option>
        </select>
        <br><br>
        <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
    </form>
</div> --}}

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href=""><i class="fa fa-home"></i> Home</a>
                    <a href="">Checkout</a>
                    <span>{{ $booking->screening->film->title }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="anime-details spad">
    <div class="container">
        <div class="anime__details__content">
            <div class="row">
                <div class="col-lg-3">
                    <div class="anime__details__pic set-bg" data-setbg="{{ asset('storage/' . $booking->screening->film->poster) }}">
                        <div class="comment"><i class="fa fa-comments"></i> 11</div>
                        <div class="view"><i class="fa fa-eye"></i> 9141</div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Alert untuk menampilkan error -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Alert jika ada pesan flash error -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="anime__details__text">
                        <div class="anime__details__title">
                            <h3>{{ $booking->screening->film->title }}</h3>
                        </div>

                        <p>{{ $booking->screening->film->description }}</p>
                        <div class="anime__details__widget">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <ul>
                                        <li><span>Jumlah Kursi:</span> {{ $booking->seats_booked }}</li>
                                        <li><span>Total Harga:</span> Rp {{ number_format($totalHarga, 0, ',', '.') }}</li>

                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="anime__details__btn">
                            <form action="{{ route('payments.create', $booking->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" id="" value="bank_transfer">
                                <br><br>
                                <button type="submit" class="btn btn-primary watch-btn">Lanjut ke Pembayaran</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
