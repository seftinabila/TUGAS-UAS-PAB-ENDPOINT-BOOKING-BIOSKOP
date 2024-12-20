
@extends('layouts.front.app')

@section('content')
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="hero__slider owl-carousel">
                @foreach ($films as $film)
                <div class="hero__items set-bg" data-setbg="{{ asset('storage/' . $film->poster) }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">{{ $film->genre }}</div>
                                <h2>{{ $film->title }}</h2>
                                <p>{{ Str::limit($film->description, 50) }}</p>
                                <a href="#"><span>Booking Tiket</span> <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Sedang Tayang</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="#" class="primary-btn">Lihat Semua <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($films as $film)

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <a href="{{ url('/bookings/create', $film->id) }}">
                                        <div class="product__item__pic set-bg" data-setbg="{{ asset('storage/' . $film->poster) }}">
                                            <div class="ep">18 / 18</div>
                                            <div class="comment"><i class="fa fa-comments"></i> 11</div>
                                            <div class="view"><i class="fa fa-eye"></i> 9141</div>
                                        </div>

                                        <div class="product__item__text">
                                            <ul>
                                                <li>Active</li>
                                                <li>Movie</li>
                                            </ul>
                                            <h5><a href="#">{{ $film->title }}</a></h5>
                                        </div>
                                    </a>
                                    </div>
                                </div>

                            @endforeach

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
</div>
</div>
</div>
</section>
<!-- Product Section End -->
@endsection
