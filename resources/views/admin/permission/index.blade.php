@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Manajemen Permission</h1>
                <div class="ml-auto">
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Permission</a>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Nama Permission</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $permission)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td class="pt_10 pb_10">
                                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin ingin menghapus permission ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
