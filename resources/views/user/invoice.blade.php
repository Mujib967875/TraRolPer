@extends('front.layout.master')

@section('main_content')
@php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Invoice: {{ $invoice_no }}</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user_booking') }}">Pemasanan</a></li>
                        <li class="breadcrumb-item active">Faktur: {{ $invoice_no }}</li>
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
                <div class="invoice-container" id="print_invoice">
                    
                    <div class="invoice-top">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-border-0">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <img src="uploads/logo.png" alt="" class="h-60">
                                                </td>
                                                <td class="w-50">
                                                    <div class="invoice-top-right">
                                                        <h4>Faktur</h4>
                                                        <h5>Nomor Pesanan: {{ $invoice_no }}</h5>
                                                        <h5>Tanggal Pemesanan: {{ $booking->created_at->format('Y-m-d') }}</h5>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-middle">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-border-0">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <div class="invoice-middle-left">
                                                        <h4>Faktur Untuk:</h4>
                                                        <p class="mb_0">{{ Auth::guard('web')->user()->name }}</p>
                                                        <p class="mb_0">{{ Auth::guard('web')->user()->email }}</p>
                                                        <p class="mb_0">{{ Auth::guard('web')->user()->phone }}</p>
                                                        <p class="mb_0">{{ Auth::guard('web')->user()->address }}</p>
                                                        <p class="mb_0">{{ Auth::guard('web')->user()->city }}, {{ Auth::guard('web')->user()->state }}, {{ Auth::guard('web')->user()->country }}, {{ Auth::guard('web')->user()->zip }}</p>
                                                    </div>
                                                </td>
                                                <td class="w-50">
                                                    <div class="invoice-middle-right">
                                                        <h4>Faktur dari:</h4>
                                                        <p class="mb_0">{{ env('APP_NAME') }}</p>
                                                        <p class="mb_0">{{ $admin_data->name }}</p>
                                                        <p class="mb_0 color_6d6d6d">{{ $admin_data->email }}</p>
                                                        <p class="mb_0 color_6d6d6d">Status Pembayaran: {{ $booking->payment_status }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-item">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered invoice-item-table">
                                        <tbody>
                                            <tr>
                                                <th>SL</th>
                                                <th>Paket</th>
                                                <th>Tanggal Mulai Tur</th>
                                                <th>Tanggal Berakhir Tur</th>
                                                <th>Harga</th>
                                                <th>Jumlah Orang</th>
                                                <th>Total Harga </th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $booking->package->name }}</td>
                                                <td>{{ $booking->tour->tour_start_date }}</td>
                                                <td>{{ $booking->tour->tour_end_date }}</td>
                                                <td>Rp. {{ number_format($booking->package->price) }}</td>
                                                <td>{{ $booking->total_person }}</td>
                                                <td>Rp. {{ number_format($booking->paid_amount) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-bottom">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-border-0">
                                        <tbody>
                                            <td class="w-70 invoice-bottom-payment">
                                                <h4>Metode Pembayaran :</h4>
                                                @if ($booking->payment_method == 'Midtrans')
                                                    <p> Non-tunai </p>
                                                    @elseif ($booking->payment_method == 'Cash')
                                                    <p> Cash </p>
                                                    @elseif ($booking->payment_method == 'Stripe')
                                                    <p> Stripe </p>
                                                    @elseif ($booking->payment_method == 'PayPal')
                                                    <p> Paypal </p>
                                                @endif
                                            </td>
                                            <td class="w-30 tar invoice-bottom-total-box">
                                                <p class="mb_0"><b>Total: Rp. {{ $booking->paid_amount }}</b></p>
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="print-button mt_25">
                    <a onclick="printInvoice()" href="javascript:;" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</a>
                </div>
                <script>
                    function printInvoice() {
                        let body = document.body.innerHTML;
                        let data = document.getElementById('print_invoice').innerHTML;
                        document.body.innerHTML = data;
                        window.print();
                        document.body.innerHTML = body;
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
