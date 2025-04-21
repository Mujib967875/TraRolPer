@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
        <div class="main-content">
            <section class="section">
                <div class="section-header justify-content-between">
                    <h1>Sampah Bagian Kategori</h1>
                    <div class="ml-auto">
                        <a href="{{ route('admin_blog_category_index') }}" class="btn btn-primary mr_10"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
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
                                                    <th>Nama Kategori</th>
                                                    <th>Slug</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($blog_categories as $blog_category)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $blog_category->name }}</td>
                                                    <td>{{ $blog_category->slug }}</td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="{{ route('admin_blog_category_trash_restore', $blog_category->id) }}" class="btn btn-primary"><i class="fas fa-arrows-alt-v"></i> Pulihkan</a>

                                                        <a href="{{ route('admin_blog_category_trash_delete', $blog_category->id) }}" class="btn btn-danger" onClick="return confirm('Kategori akan dihapus secara permanen. Apakah anda yakin?');"><i class="fas fa-trash"></i> Hapus Permanen</a>
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
