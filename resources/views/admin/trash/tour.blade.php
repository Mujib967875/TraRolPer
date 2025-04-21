@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
        <div class="main-content">
            <section class="section">
                <div class="section-header justify-content-between">
                    <h1>Sampah Bagia Tur</h1>
                    <div class="ml-auto">
                        <a href="{{ route('admin_tour_index') }}" class="btn btn-primary"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
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
                                                    <th>Paket Info</th>
                                                    <th>Tanggal Mulai</th>
                                                    <th>Tanggal Berakhir</th>
                                                    <th>Tanggal Pemesanan</th>
                                                    <th>Total Kursi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tours as $tour)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ $tour->package->name }}<br>
                                                        <a href="{{ route('package', $tour->package->slug) }}" target="_blank">Lihat Selengkapnya</a>
                                                    </td>
                                                    <td>{{ $tour->tour_start_date }}</td>
                                                    <td>{{ $tour->tour_end_date }}</td>
                                                    <td>{{ $tour->booking_end_date }}</td>
                                                    <td>
                                                        @if ($tour->total_seat == -1)
                                                        Tidak Ada Batas
                                                        @else
                                                        {{ $tour->total_seat }}
                                                        @endif
                                                    </td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="{{ route('admin_tour_trash_restore', $tour->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                        <a href="{{ route('admin_tour_trash_delete', $tour->id) }}" class="btn btn-danger" onClick="return confirm('Tur ini akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Hapus Permanen</a>

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
                </div>
            </section>
        </div>
@endsection
