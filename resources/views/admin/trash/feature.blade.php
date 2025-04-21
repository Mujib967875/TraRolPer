@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Sampah Bagian Fitur</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin_feature_index') }}" class="btn btn-primary mr_10"><i
                            class="fas fa-arrow-circle-left"></i> Kembali</a>
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
                                                <th>Ikon Sekilas</th>
                                                <th>Ikon</th>
                                                <th>Judul</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($features as $feature)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><i class="{{ $feature->icon }} fz_30"></i></td>
                                                    <td>{{ $feature->icon }}</td>
                                                    <td>{{ $feature->heading }}</td>
                                                    <td class="pt_10 pb_10">

                                                        <a href="{{ route('admin_feature_trash_restore', $feature->id) }}"
                                                            class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i>
                                                            Restore</a>

                                                        <a href="{{ route('admin_feature_trash_delete', $feature->id) }}"
                                                            class="btn btn-danger"
                                                            onClick="return confirm('Fitur ini akan dihapus secara permanen. Apakah anda yakin?');"><i
                                                                class="fas fa-trash"></i> Delete Permanently</a>

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
