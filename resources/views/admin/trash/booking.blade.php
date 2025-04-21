@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Sampah Bagian Pemesanan</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin_tour_booking', [$tours->id, $tours->package->id]) }}" class="btn btn-primary"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>No faktur</th>
                                                <th>Info Pengguna</th>
                                                <th>Jumlah Bayar</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Status Pembayaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->invoice_no }}</td>
                                                <td>
                                                    <strong>Nama : </strong>{{ $data->user->name }}<br>
                                                    <strong>Email : </strong>{{ $data->user->email }}<br>
                                                    <strong>Telpon : </strong>{{ $data->user->phone }}
                                                </td>
                                                <td>{{ $data->paid_amount }}</td>
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
                                                        <span class="badge badge-success">Tuntas</span>
                                                    @elseif ($data->payment_status == 'Pending')
                                                        <span class="badge badge-danger mb-3">Tertunda</span>
                                                    @elseif ($data->payment_status == 'Cancel')
                                                        <span class="badge badge-warning">Dibatalkan</span>
                                                    @elseif ($data->payment_status == 'Expired')
                                                        <span class="badge badge-secondary">Kadaluarsa</span>
                                                    @elseif ($data->payment_status == 'Denied')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="{{ route('admin_booking_trash_restore', $data->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                    <a href="{{ route('admin_booking_trash_delete', $data->id) }}" class="btn btn-danger" onClick="return confirm('Pemesanan ini akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Delete</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
