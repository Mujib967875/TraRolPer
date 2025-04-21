@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
        <div class="main-content">
            <section class="section">
                <div class="section-header justify-content-between">
                    <h1>Pengguna</h1>
                    <div class="ml-auto">
                        <a href="{{ route('admin_user_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengguna</a>
                        <a href="{{ route('admin_user_trash') }}" class="btn btn-danger"><i class="fas fa-trash"></i> Sampah ( {{ $trashCount }} )</a>
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
                                                    <th>Nama</th>
                                                    <th>Foto</th>
                                                    <th>Email</th>
                                                    <th>Telpon</th>
                                                    <th>Info Alamat</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if ($item->photo)
                                                        <img src="{{ asset('uploads/'.$item->photo) }}" class="w_100">
                                                        @else
                                                        <img src="{{ asset('uploads/default.png') }}" class="w_100">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $item->name }}
                                                    </td>
                                                    <td>
                                                        {{ $item->email }}
                                                    </td>
                                                    <td>
                                                        {{ $item->phone }}
                                                    </td>
                                                    <td>
                                                        Negara : {{ $item->country }}<br>
                                                        Alamat : {{ $item->address }}<br>
                                                        Daerah : {{ $item->state }}<br>
                                                        Kota : {{ $item->city }}<br>
                                                        Kode Pos : {{ $item->zip }}<br>
                                                    </td>
                                                    <td>
                                                        @if ($item->status == 1)
                                                        <span class="badge badge-success">Aktif</span>
                                                        @else
                                                        <span class="badge badge-danger">Tertunda</span>
                                                        @endif
                                                    </td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="{{ route('admin_user_edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="{{ route('admin_user_delete', $item->id) }}" class="btn btn-danger" onClick="return confirm('Pengguna ini akan dipindahkan ke sampah. Apakah Anda yakin?');"><i class="fas fa-trash"></i></a>
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
