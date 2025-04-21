@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Sampah Bagian Sponsor</h1>
                <div class="ml-auto">
                    <a href="{{ route('sponsor_index') }}" class="btn btn-primary"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Foto</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sponsor_photos as $sponsor_photo)
                                            <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ asset('trash/'.$sponsor_photo->photo) }}" class="w_150">
                                            </td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_sponsor_trash_restore', $sponsor_photo->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                <a href="{{ route('admin_sponsor_trash_delete', $sponsor_photo->id) }}" class="btn btn-danger" onClick="return confirm('Sponsor ini akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Hapus Permanen</a>
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
