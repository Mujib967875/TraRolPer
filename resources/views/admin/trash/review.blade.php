@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
        <div class="main-content">
            <section class="section">
                <div class="section-header justify-content-between">
                    <h1>Sampah Bagian Ulasan</h1>
                    <div class="ml-auto">
                        <a href="{{ route('admin_review_index') }}" class="btn btn-primary mr_10"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
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
                                                    <th>Paket</th>
                                                    <th>Pengguna</th>
                                                    <th>Rating</th>
                                                    <th>Komentar</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($reviews as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ $item->package->name }}<br>
                                                        <a href="{{ route('package',$item->package->slug) }}" target="_blank">Lihat Selengkapnya</a>
                                                    </td>
                                                    <td>
                                                        {{ $item->user->name }}<br>
                                                        {{ $item->user->email }}
                                                    </td>
                                                    <td>{{ $item->rating }}</td>
                                                    <td>{{ $item->comment }}</td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="{{ route('admin_review_trash_restore', $item->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                        <a href="{{ route('admin_review_trash_delete', $item->id) }}" class="btn btn-danger" onClick="return confirm('Ulasan ini akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Hapus Permanen</a>
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
