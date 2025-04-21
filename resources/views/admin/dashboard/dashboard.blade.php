@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row mb-5">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Slider</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_sliders }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-pen"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Testimoni</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_testimonials }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Anggota Tim</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_team_members }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fab fa-blogger-b"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Postingan</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_posts }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-plane"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Destinasi</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_destinations }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Paket</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_packages }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Pengguna</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_users }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Pengikut</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_subscribers }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Tur</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_tours }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- <form action="{{ route('admin_dashboard_filter') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label>Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            @if(count($bookings))
            <div class="d-flex justify-content-between align-items-center">
                <div class="alert alert-success">
                    Total Pemasukan: <strong>Rp {{ number_format($bookings->sum('paid_amount'), 0, ',', '.') }}</strong>
                </div>
                <div class="align-items-center">
                    <a href="{{ route('admin.bookings.export.pdf', request()->only(['start_date', 'end_date'])) }}" class="btn btn-danger mb-3">
                        <i class="fas fa-file-pdf"></i> Simpan Ke PDF
                    </a>
                    <a href="{{ route('admin.bookings.export.excel', request()->only(['start_date', 'end_date'])) }}" class="btn btn-success mb-3">
                        <i class="fas fa-file-excel"></i> Simpan Ke Excel
                    </a>
                </div>
            </div>
            @endif


            <div class="section-body mb-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>No Faktur</th>
                                                <th>Info Pengguna</th>
                                                <th>Jumlah Orang</th>
                                                <th>Total Bayar</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Status Pembayaran</th>
                                                <th>Faktur</th>
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
                                                <td>{{ $data->total_person }}</td>
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
                                                        <span class="badge badge-danger mb-3">Tertunda</span><br>
                                                    @elseif ($data->payment_status == 'Cancel')
                                                        <span class="badge badge-warning">Dibatalkan</span>
                                                    @elseif ($data->payment_status == 'Expired')
                                                        <span class="badge badge-secondary">Kadaluarsa</span>
                                                    @elseif ($data->payment_status == 'Denied')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin_tour_invoice',$data->invoice_no) }}" class="badge badge-primary text-decoration-none" target="_blank">Lihat Faktur</a>
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
            </div> --}}


            <form method="GET" action="{{ route('admin_dashboard') }}" class="mb-3">
                <select name="year" onchange="this.form.submit()" class="form-control w-auto d-inline-block">
                    @for ($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>

            <canvas id="incomeChart" class="mb-5" height="100"></canvas>


        </section>
    </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
            datasets: [{
                label: 'Total Pemasukan',
                data: [
                    @for ($i = 1; $i <= 12; $i++)
                        {{ $monthlyIncome[$i] ?? 0 }},
                    @endfor
                ],
                backgroundColor: '#4CAF50'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Pemasukan Bulanan Tahun {{ $year }}'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp. ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>


@endsection
