@extends('front.layout.master')

@section('main_content')
    @php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Bookings</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Pemesanan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content user-panel pt_70 pb_70">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-12">
                    <div class="card">
                        @include('user.sidebar')
                    </div>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>SL</th>
                                    <th>Destinasi</th>
                                    <th>No Faktur</th>
                                    <th>Jumlah Orang</th>
                                    <th>Jumlah yang Dibayar</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th class="w-100">Aksi</th>
                                </tr>
                                @foreach ($all_data as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->package->name }}</td>
                                    <td>{{ $data->invoice_no }}</td>
                                    <td>{{ $data->total_person }}</td>
                                    <td>Rp. {{ $data->paid_amount }}</td>
                                    <td>
                                        @if ($data->payment_method == 'Midtrans')
                                            <p> Non-tunai </p>
                                            @elseif ($data->payment_method == 'Cash')
                                            <p> Cash </p>
                                            @elseif ($data->payment_method == 'Stripe')
                                            <p> Stripe </p>
                                            @elseif ($data->payment_method == 'PayPal')
                                            <p> Paypal </p>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->payment_status == 'Completed')
                                            <div class="badge bg-success">Tuntas</div>
                                        @elseif ($data->payment_status == 'Pending')
                                            <div class="badge bg-warning">Tertunda</div>
                                        @elseif ($data->payment_status == 'Denied')
                                            <div class="badge bg-danger">Ditolak</div>
                                        @elseif ($data->payment_status == 'Expired')
                                            <div class="badge bg-warning">Kadaluarsa</div>
                                        @elseif ($data->payment_status == 'Cancelled')
                                            <div class="badge bg-primary">Dibatalkan</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-secondary btn-sm mb-1 w-100-p" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $loop->iteration }}">Detail</a>
                                        <a href="{{ route('user_invoice', $data->invoice_no) }}" class="btn btn-secondary btn-sm w-100-p">Invoice</a>
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{ $loop->iteration }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>No Faktur:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $data->invoice_no }}
                                                    </div>
                                                </div>
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>Detail Paket:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <b>Nama :</b> {{ $data->package->name }}<br>
                                                        <a href="{{ route('package', $data->package->slug) }}" target="_blank">Lihat Detail Paket</a>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>Detail Tur:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <b>Tanggal Mulai : </b>
                                                        {{ $data->tour->tour_start_date ?? 'Tour tidak ditemukan.' }}<br>
                                                        <b>Tanggal Berakhir : </b>
                                                        {{ $data->tour->tour_end_date ?? 'Tour tidak ditemukan.' }}<br>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>Jumlah Orang:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $data->total_person }}
                                                    </div>
                                                </div>
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>Jumlah yang Dibayar:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        Rp. {{ $data->paid_amount }}
                                                    </div>
                                                </div>
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>Metode Pembayaran:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $data->payment_method }}
                                                    </div>
                                                </div>
                                                <div class="mb-3 row modal-seperator">
                                                    <div class="col-md-5">
                                                        <b>Status Pembayaran:</b>
                                                    </div>
                                                    <div class="col-md-7">
                                                        @if ($data->payment_status == 'Completed')
                                                            <div class="badge bg-success">Tuntas</div>
                                                        @elseif ($data->payment_status == 'Pending')
                                                            <div class="badge bg-warning">Tertunda</div>
                                                        @elseif ($data->payment_status == 'Denied')
                                                            <div class="badge bg-danger">Ditolak</div>
                                                        @elseif ($data->payment_status == 'Expired')
                                                            <div class="badge bg-warning">Kadaluarsa</div>
                                                        @elseif ($data->payment_status == 'Cancelled')
                                                            <div class="badge bg-primary">Dibatalkan</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // Modal -->

                                @endforeach
                            </tbody>
                            <div class="row">
                                <div class="col-md-12" style="overflow: hidden">
                                    {{ $all_data->appends($_GET)->links() }}
                                </div>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
