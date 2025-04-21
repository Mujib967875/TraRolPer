@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="invoice">
                        <h3>No Faktur :: {{ $booking->invoice_no }}</h3>
                        <div class="invoice-print">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>No Faktur : </td>
                                            <td>{{ $booking->invoice_no }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pengguna Info : </td>
                                            <td>
                                                Nama : {{ $booking->user->name }}<br>
                                                Email : {{ $booking->user->email }}<br>
                                                Telpon : {{ $booking->user->phone }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Disetujui Oleh : </td>
                                            <td>
                                                Nama : {{ Auth::guard('admin')->user()->name }}<br>
                                                {{-- Email : {{ Auth::guard('admin')->user()->email }}<br> --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Infomasi Tur : </td>
                                            <td>
                                                Tanggal Mulai Tur :{{ $booking->tour->tour_start_date }}<br>
                                                Tanggal Berakhir Tur :{{ $booking->tour->tour_end_date }}<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Infomasi Paket : </td>
                                            <td>
                                                Nama Paket : {{ $booking->package->name }}<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pemesanan : </td>
                                            <td>{{ $booking->created_at->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Metode Pembayaran : </td>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>Status Pembayaran : </td>
                                            <td>
                                                @if ($booking->payment_status == 'Completed')
                                                <span class="badge badge-success">Tuntas</span>
                                            @elseif ($booking->payment_status == 'Pending')
                                                <span class="badge badge-danger mb-3">Tertunda</span>
                                            @elseif ($booking->payment_status == 'Cancel')
                                                <span class="badge badge-warning">Dibatalkan</span>
                                            @elseif ($booking->payment_status == 'Expired')
                                                <span class="badge badge-secondary">Kadaluarsa</span>
                                            @elseif ($booking->payment_status == 'Denied')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah Orang : </td>
                                            <td>{{ $booking->total_person }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Bayar : </td>
                                            <td>Rp. {{ number_format($booking->paid_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-md-right">
                            <a href="javascript:window.print();" class="btn btn-warning btn-icon icon-left text-white print-invoice-button"><i class="fas fa-print"></i> Cetak</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
@endsection
