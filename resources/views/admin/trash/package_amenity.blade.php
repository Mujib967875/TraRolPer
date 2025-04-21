@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Sampah Bagian Fasilitas Paket</h1>
                <div class="ml-auto">
                    <a href="{{ route('package_amenities', $package->id) }}" class="btn btn-primary"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <h4>Termasuk</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Nama Fasilitas</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($package_amenities_includes as $item)
                                            <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->amenities_name }}
                                            </td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_package_amenity_trash_restore', $item->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                <a href="{{ route('admin_package_amenity_trash_delete', $item->id) }}" class="btn btn-danger" onClick="return confirm('Fasilitas Paket ini akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Hapus Permanen</a>
                                            </td>
                                        </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <h4>Tidak Termasuk</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Nama Fasilitas</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($package_amenities_excludes as $item)
                                            <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->amenities_name }}
                                            </td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_package_amenity_trash_restore', $item->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                <a href="{{ route('admin_package_amenity_trash_delete', $item->id) }}" class="btn btn-danger" onClick="return confirm('Fasilitas Paket ini akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Hapus Permanen</a>
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
    </div>
    </div>
    </section>
    </div>
@endsection
