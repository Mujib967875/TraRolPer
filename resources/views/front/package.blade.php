@extends('front.layout.master')

@section('main_content')
    <div class="page-top page-top-package" style="background-image: url({{ asset('uploads/' . $package->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{ $package->name }}</h2>
                    <h3><i class="fas fa-plane-departure"></i> {{ $package->destination->name }}</h3>


                    @if ($package->total_score || $package->total_rating)
                        <div class="review">
                            <div class="set">
                                @php
                                    $package_rating = $package->total_score / $package->total_rating;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $package_rating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $package_rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span>({{ $package_rating }} dari 5)</span>
                        </div>
                    @else
                        <div class="review">
                            <div class="set">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                            <span>Tidak Ada ulasan</span>
                        </div>
                    @endif


                    <div class="price">
                        Rp{{ number_format($package->price) }} @if ($package->price != '')
                            <del>Rp{{ number_format($package->old_price) }}</del>
                        @endif
                    </div>
                    <div class="person">
                        per orang
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="package-detail pt_50 pb_50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <div class="main-item mb_50">

                        <ul class="nav nav-tabs d-flex justify-content-center" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-1" data-bs-toggle="tab"
                                    data-bs-target="#tab-1-pane" type="button" role="tab" aria-controls="tab-1-pane"
                                    aria-selected="true">Detail</button>
                            </li>
                            @if ($package_itineraries->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-2" data-bs-toggle="tab"
                                        data-bs-target="#tab-2-pane" type="button" role="tab"
                                        aria-controls="tab-2-pane" aria-selected="false">Rencana</button>
                                </li>
                            @endif
                            @if ($package->map != '')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-3" data-bs-toggle="tab"
                                        data-bs-target="#tab-3-pane" type="button" role="tab"
                                        aria-controls="tab-3-pane" aria-selected="false">Lokasi</button>
                                </li>
                            @endif
                            @if ($package_photos->count() > 0 || $package_videos->count() > 0 || $package_amenities->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-4" data-bs-toggle="tab"
                                        data-bs-target="#tab-4-pane" type="button" role="tab"
                                        aria-controls="tab-4-pane" aria-selected="false">Galeri</button>
                                </li>
                            @endif
                            @if ($package_faqs->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-5" data-bs-toggle="tab"
                                        data-bs-target="#tab-5-pane" type="button" role="tab"
                                        aria-controls="tab-5-pane" aria-selected="false">Pertanyaan</button>
                                </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-6" data-bs-toggle="tab" data-bs-target="#tab-6-pane"
                                    type="button" role="tab" aria-controls="tab-6-pane"
                                    aria-selected="false">Ulasan</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-7" data-bs-toggle="tab" data-bs-target="#tab-7-pane"
                                    type="button" role="tab" aria-controls="tab-7-pane" aria-selected="false">Tanya
                                    jawab</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-8" data-bs-toggle="tab" data-bs-target="#tab-8-pane"
                                    type="button" role="tab" aria-controls="tab-8-pane"
                                    aria-selected="false">Pemesanan</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab-1-pane" role="tabpanel"
                                aria-labelledby="tab-1" tabindex="0">
                                <!-- Detail -->
                                <h2 class="mt_30">Detail</h2>
                                <p>
                                    {!! $package->description !!}
                                </p>

                                @if ($package_amenities_includes->count() > 0)
                                    <h2 class="mt_30">Termasuk</h2>
                                    <div class="amenity">
                                        <div class="row">
                                            @foreach ($package_amenities_includes as $item)
                                                <div class="col-lg-3 mb_15">
                                                    <i class="fas fa-check"></i> {{ $item->amenities_name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($package_amenities_excludes->count() > 0)
                                    <h2 class="mt_30">Tidak Termasuk</h2>
                                    <div class="amenity">
                                        <div class="row">
                                            @foreach ($package_amenities_excludes as $item)
                                                <div class="col-lg-3 mb_15">
                                                    <i class="fas fa-times"></i> {{ $item->amenities_name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <!-- // Detail -->


                            </div>

                            <div class="tab-pane fade" id="tab-2-pane" role="tabpanel" aria-labelledby="tab-2"
                                tabindex="0">
                                <!-- Itinerary -->
                                <h2 class="mt_30">Rencana perjalanan</h2>
                                <div class="tour-plan">

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            @foreach ($package_itineraries as $item)
                                                <tr>
                                                    <td><b>{{ $item->name }}</b></td>
                                                    <td>
                                                        {!! $item->description !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>

                                <!-- // Itinerary -->
                            </div>

                            <div class="tab-pane fade" id="tab-3-pane" role="tabpanel" aria-labelledby="tab-3"
                                tabindex="0">
                                <!-- Location -->
                                <h2 class="mt_30">Lokasi</h2>
                                <div class="location-map">
                                    {!! $package->map !!}
                                </div>
                                <!-- // Location -->
                            </div>

                            <div class="tab-pane fade" id="tab-4-pane" role="tabpanel" aria-labelledby="tab-4"
                                tabindex="0">
                                <!-- Gallery -->

                                @if ($package_amenities->count() > 0)
                                    <h2 class="mt_30">Foto Fasilitas</h2>

                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="filter-buttons mb-5 mt-2 ">
                                            <button class="filter-btn btn btn-outline-primary active btn-sm mb-3" data-filter="*">All</button>
                                            @foreach ($package_amenities as $amenity)
                                                @if ($amenity->photo->count() > 0)
                                                    <button class="filter-btn btn btn-outline-primary btn-sm mb-3" data-filter=".facility-{{ $amenity->id }}">
                                                        {{ $amenity->amenities_name }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="photo-all gallery-grid">
                                        <div class="row">
                                            @foreach ($package_amenities as $amenity)
                                                @foreach ($amenity->photo as $photo)
                                                    <div class="col-md-6 col-lg-3 facility-{{ $amenity->id }}">
                                                        <div class="item">
                                                            <a href="{{ asset('uploads/' . $photo->photo) }}" class="magnific">
                                                                <img class="img-fluid" src="{{ asset('uploads/' . $photo->photo) }}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif



                                @if ($package_photos->count() > 0)
                                    <h2 class="mt_30">
                                        Foto
                                    </h2>
                                    <div class="photo-all">
                                        <div class="row">
                                            @foreach ($package_photos as $item)
                                                <div class="col-md-6 col-lg-3">
                                                    <div class="item">
                                                        <a href="{{ asset('uploads/' . $item->photo) }}" class="magnific">
                                                            <img src="{{ asset('uploads/' . $item->photo) }}"
                                                                alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($package_videos->count() > 0)
                                    <h2 class="mt_30">
                                        Video
                                    </h2>
                                    <div class="video-all">
                                        <div class="row">
                                            @foreach ($package_videos as $item)
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="item">
                                                        <a class="video-button"
                                                            href="http://www.youtube.com/watch?v={{ $item->video }}">
                                                            <img src="http://img.youtube.com/vi/{{ $item->video }}/0.jpg"
                                                                alt="">
                                                            <div class="icon">
                                                                <i class="far fa-play-circle"></i>
                                                            </div>
                                                            <div class="bg"></div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <!-- // Gallery -->
                            </div>

                            <div class="tab-pane fade" id="tab-5-pane" role="tabpanel" aria-labelledby="tab-5"
                                tabindex="0">
                                <!-- FAQ -->
                                <h2 class="mt_30">Pertanyaan yang Sering Diajukan</h2>
                                <div class="faq-package">
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($package_faqs as $item)
                                            <div class="accordion-item mb_30">
                                                <h2 class="accordion-header" id="heading_{{ $loop->iteration }}">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse_{{ $loop->iteration }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse_{{ $loop->iteration }}">
                                                        {{ $item->question }}
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{ $loop->iteration }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="heading_{{ $loop->iteration }}"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        {!! $item->answer !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- // FAQ -->
                            </div>

                            <div class="tab-pane fade" id="tab-6-pane" role="tabpanel" aria-labelledby="tab-6"
                                tabindex="0">
                                <!-- Review -->
                                <div class="review-package">

                                    <h2>Ulasan ({{ $reviews->count() }})</h2>

                                    @forelse ($reviews as $item)
                                        <div class="review-package-section">
                                            <div class="review-package-box d-flex justify-content-start">
                                                <div class="left">
                                                    @if ($item->user->photo == '')
                                                        <img src="{{ asset('uploads/default.png') }}" alt="">
                                                    @else
                                                        <img src="{{ asset('uploads/' . $item->user->photo) }}">
                                                    @endif
                                                </div>
                                                <div class="right">
                                                    <div class="name">{{ $item->user->name }}</div>
                                                    <div class="date">{{ $item->created_at->format('Y-m-d') }}</div>
                                                    <div class="review mb-2">
                                                        <div class="set">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $item->rating)
                                                                    <i class="fas fa-star"></i>
                                                                @else
                                                                    <i class="far fa-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="text">
                                                        {!! $item->comment !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-info">
                                            Tidak Ada Ulasan.
                                        </div>
                                    @endforelse

                                    <div class="mt_40"></div>

                                    <h2>Tinggalkan Ulasan Anda</h2>

                                    @if (Auth::guard('web')->check())
                                        @php
                                            $review_possible = App\Models\Booking::where('package_id', $package->id)
                                                ->where('user_id', Auth::guard('web')->user()->id)
                                                ->where('payment_status', 'Completed')
                                                ->count();
                                        @endphp

                                        @if ($review_possible > 0)
                                            @php
                                                App\Models\Review::where('package_id', $package->id)
                                                    ->where('user_id', Auth::guard('web')->user()->id)
                                                    ->count() > 0
                                                    ? ($reviewed = true)
                                                    : ($reviewed = false);
                                            @endphp

                                            @if ($reviewed == false)
                                                <form action="{{ route('review_submit') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                                    <div class="mb-3">
                                                        <div class="give-review-auto-select">
                                                            <input type="radio" id="star5" name="rating"
                                                                value="5" /><label for="star5" title="5 stars"><i
                                                                    class="fas fa-star"></i></label>
                                                            <input type="radio" id="star4" name="rating"
                                                                value="4" /><label for="star4" title="4 stars"><i
                                                                    class="fas fa-star"></i></label>
                                                            <input type="radio" id="star3" name="rating"
                                                                value="3" /><label for="star3" title="3 stars"><i
                                                                    class="fas fa-star"></i></label>
                                                            <input type="radio" id="star2" name="rating"
                                                                value="2" /><label for="star2" title="2 stars"><i
                                                                    class="fas fa-star"></i></label>
                                                            <input type="radio" id="star1" name="rating"
                                                                value="1" /><label for="star1" title="1 star"><i
                                                                    class="fas fa-star"></i></label>
                                                        </div>
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', (event) => {
                                                                const stars = document.querySelectorAll('.star-rating label');
                                                                stars.forEach(star => {
                                                                    star.addEventListener('click', function() {
                                                                        stars.forEach(s => s.style.color = '#ccc');
                                                                        this.style.color = '#f5b301';
                                                                        let previousStar = this.previousElementSibling;
                                                                        while (previousStar) {
                                                                            if (previousStar.tagName === 'LABEL') {
                                                                                previousStar.style.color = '#f5b301';
                                                                            }
                                                                            previousStar = previousStar.previousElementSibling;
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control" rows="3" name="comment" placeholder="Comment"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="alert alert-danger">Anda telah memberikan ulasan.</div>
                                            @endif
                                        @else
                                            <div class="mb-3">
                                                <p class="text-danger">Anda harus memesan paket ini untuk memberikan
                                                    ulasan.</p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="mb-3">
                                            <a href="{{ route('login') }}" class="text-danger text-decoration-underline">
                                                Masuk untuk memberi Ulasan</a>
                                        </div>
                                    @endif
                                </div>
                                <!-- // Review -->
                            </div>

                            <div class="tab-pane fade" id="tab-7-pane" role="tabpanel" aria-labelledby="tab-7"
                                tabindex="0">
                                <!-- Enquery -->
                                <h2 class="mt_30">Ajukan Pertanyaan Anda.</h2>
                                <div class="enquery-form">
                                    <form action="{{ route('enquery_form_submit', $package->id) }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Nama Lengkap">
                                        </div>
                                        <div class="mb-3">
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Alamat Email">
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="phone"
                                                placeholder="No Telepon">
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control h-150" name="message" rows="3" placeholder="Pesan"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">
                                                Kirim Pesan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- // Enquery -->
                            </div>

                            <div class="tab-pane fade" id="tab-8-pane" role="tabpanel" aria-labelledby="tab-8"
                                tabindex="0">



                                @if ($tours->count() > 0)
                                    <form action="{{ route('payment') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                @php $i=0; @endphp
                                                @foreach ($tours as $item)
                                                    @if ($item->booking_end_date < date('Y-m-d'))
                                                        @continue
                                                    @endif
                                                    @php
                                                        $i++;
                                                        $total_booked_seats = 0;
                                                        $all_data = App\Models\Booking::where('tour_id', $item->id)
                                                            ->where('package_id', $package->id)
                                                            ->get();
                                                        foreach ($all_data as $data) {
                                                            $total_booked_seats += $data->total_person;
                                                        }

                                                        if ($item->total_seat == '-1') {
                                                            $remaining_seats = 'Unlimited';
                                                        } else {
                                                            $remaining_seats = $item->total_seat - $total_booked_seats;
                                                        }
                                                    @endphp
                                                    <h2 class="mt_30">
                                                        <input type="radio" name="tour_id" value="{{ $item->id }}"
                                                            @if ($i == 1) checked @endif>
                                                        Tur {{ $i }}
                                                    </h2>
                                                    <div class="summary">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <td><b>Tanggal Mulai</b></td>
                                                                    <td>
                                                                        {{ $item->tour_start_date }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Tanggal Akhir</b></td>
                                                                    <td>
                                                                        {{ $item->tour_end_date }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Tanggal Akhir Pemesanan</b></td>
                                                                    <td class="text-danger">
                                                                        {{ $item->booking_end_date }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Jumlah Kursi</b></td>
                                                                    <td>
                                                                        @if ($item->total_seat == -1)
                                                                            Tidak terhingga
                                                                        @else
                                                                            {{ $item->total_seat }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Kursi yang Sudah Dipesan</b></td>
                                                                    <td>
                                                                        {{ $total_booked_seats }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Kursi yang Tersisa</b></td>
                                                                    <td>
                                                                        {{ $remaining_seats }}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-4">
                                                <h2 class="mt_30">Pembayaran</h2>
                                                <div class="summary">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="ticket_price"
                                                                        id="ticketPrice" value="{{ $package->price }}">
                                                                    <label for=""><b>Jumlah Orang</b></label>
                                                                    <input type="number"
                                                                    min="{{ $package->min_person }}"
                                                                    max="{{ $package->max_person }}"
                                                                    name="total_person"
                                                                    class="form-control"
                                                                    value="{{ $package->min_person }}"
                                                                    id="numPersons"
                                                                    oninput="calculateTotal()">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label for=""><b>Total</b></label>
                                                                    <input type="text" name=""
                                                                        class="form-control" style="cursor: not-allowed; background-color:#e9ecef ;" id="totalAmount"
                                                                        value="Rp{{ number_format($package->min_person * $package->price, 0, ',', '.') }}" readonly>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label for=""><b>Pilih Metode Pembayaran</b></label>
                                                                        {{-- <input type="radio" name="Midtrans" values="Midtrans"> --}}
                                                                    {{-- <select name="payment_method" class="form-select">
                                                                        <option value="Midtrans">Non-tunai</option>
                                                                        <option value="Cash">Cash</option>
                                                                        <option value="PayPal">PayPal</option>
                                                                        <option value="Stripe">Stripe</option>
                                                                    </select> --}}
                                                                    <div class="mb-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="payment_method" id="midtrans" value="Midtrans">
                                                                            <label class="form-check-label" for="midtrans">
                                                                                Non-tunai
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="Cash">
                                                                            <label class="form-check-label" for="cash">
                                                                                Cash
                                                                            </label>
                                                                        </div>
                                                                        {{-- <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="PayPal">
                                                                            <label class="form-check-label" for="paypal">
                                                                                PayPal
                                                                            </label>
                                                                        </div> --}}
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="Stripe">
                                                                            <label class="form-check-label" for="stripe">
                                                                                Stripe
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    @if (Auth::guard('web')->check())
                                                                        <button type="submit" id="pay-button">Bayar Sekarang</button>
                                                                    @else
                                                                        <a href="{{ route('login') }}"
                                                                            class="btn btn-primary">Login Untuk Memesan</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                            <script>
                                                function calculateTotal() {
                                                    let rawPrice = document.getElementById('ticketPrice').value || '0';
                                                    
                                                    rawPrice = rawPrice.replace(/[^0-9]/g, '');
                                                
                                                    const ticketPrice = parseInt(rawPrice);
                                                    const numPersons = parseInt(document.getElementById('numPersons').value) || 0;
                                                
                                                    const totalAmount = ticketPrice * numPersons;
                                                
                                                    document.getElementById('totalAmount').value = totalAmount.toLocaleString('id-ID', {
                                                        style: 'currency',
                                                        currency: 'IDR',
                                                        minimumFractionDigits: 0
                                                    });
                                                }
                                            </script>
                                                

                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        Tidak Ada tur yang tersedia untuk paket ini.
                                    </div>
                                @endif



                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @if ($package->include_hostel == '1')
        <h2 class="mt_30 mb-5">Pilih Penginapan</h2>

        <div class="package mb-5">
            @if ($availableHotels->count() > 0)
            <div class="col-lg-12 col-md-12">
                <div class="row">

                    @foreach ($availableHotels as $hotel)
                    <div class="col-lg-4 col-md-4">
                                <div class="item pb_25">
                                    <div class="photo">
                                        <a href="#">
                                            <img src="{{ asset('uploads/' . $hotel->photo) }}" alt="{{ $hotel->name }}">
                                        </a>
                                    </div>
                                    <div class="text">
                                        <div class="price">
                                            @if ($hotel->rooms->first() && $hotel->rooms->first()->room_price < 1)
                                                Tanpa Biaya Tambahan
                                            @else
                                                Rp {{ number_format($hotel->rooms->first()->room_price ?? 0) }}
                                            @endif
                                        </div>
                                        <h2><a href="#">{{ $hotel->name }}</a></h2>

                                        <div class="element">
                                            <div class="element-left">
                                                <i class="fas fa-th-large"></i> {{ $hotel->rooms_count ?? '0' }} Kamar
                                            </div>
                                            <div class="element-right">
                                                <i class="fas fa-clock"></i> {{ $package->package_itinerary->count() }} Hari
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between gap-2">
                                            <button class="btn btn-primary btn-sm mt-2 d-flex align-items-center gap-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseRooms{{ $hotel->id }}"
                                                aria-expanded="false"
                                                aria-controls="collapseRooms{{ $hotel->id }}">
                                                Pilih Kamar
                                                <i id="arrowIcon-{{ $hotel->id }}" class="fas fa-chevron-down"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary mt-2 btn-sm">Lihat Detail</button>
                                        </div>

                                        <div class="collapse mt-3" id="collapseRooms{{ $hotel->id }}">
                                            @forelse ($hotel->rooms as $room)
                                                <div class="border rounded p-2 mb-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <strong>{{ $room->name }}</strong><br>
                                                        Harga: Rp {{ number_format($room->room_price) }}<br>
                                                        Kapasitas: {{ $room->capacity }} orang
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input"
                                                        type="radio"
                                                        name="selected_room"
                                                        value="{{ $room->id }}"
                                                        id="room-{{ $room->id }}"
                                                        data-room-price="{{ $room->room_price }}"
                                                        {{ $room->is_book > 0 ? 'disabled' : '' }}
                                                        {{ old('selected_room') == $room->id ? 'checked' : '' }}>
                                                        @if ($room->is_book > 0)
                                                            <small class="text-danger">Sudah dipesan</small>
                                                        @else
                                                            <label class="form-check-label" for="room-{{ $room->id }}">Pilih</label>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted">Belum ada kamar untuk penginapan ini.</p>
                                            @endforelse
                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @else
                <p>Tidak ada penginapan tersedia pada tanggal ini.</p>
            @endif
        </div>
    @endif --}}



<script src="https://cdn.jsdelivr.net/npm/mixitup/dist/mixitup.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var mixer = mixitup('.gallery-grid', {
            selectors: {
                target: '.col-md-6'
            },
            animation: {
                duration: 300
            }
        });

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                var filterValue = this.getAttribute('data-filter');
                mixer.filter(filterValue);
            });
        });
    });
</script>




@endsection
