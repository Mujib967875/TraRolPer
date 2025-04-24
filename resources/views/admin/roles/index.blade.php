@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Role & Permissions</h1>
                <div class="ml-auto">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Role</a>
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
                                                <th>Nama Role</th>
                                                <th>Permissions</th>
                                                @forelse ($roles as $role)
                                                    @if ($role->name != 'super_admin')
                                                        <th>Aksi</th>
                                                    @endif
                                                @empty
                                                    <span class="badge badge-danger">Tidak bisa di Hapus dan Edit</span>
                                                @endforelse
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $role)

                                            @php
                                                $maxPermissionToShow = 10;
                                                $totalPermissions = count($role->permissions);
                                            @endphp

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @foreach($role->permissions->take($maxPermissionToShow) as $permission)
                                                        <span class="badge bg-info text-white">{{ $permission->name }}</span>
                                                    @endforeach
                                                    @if($totalPermissions > $maxPermissionToShow)
                                                        <span class="badge bg-secondary">
                                                            +{{ $totalPermissions - $maxPermissionToShow }} lainnya
                                                        </span>
                                                    @endif
                                                </td>
                                                @if ($role->name != 'super_admin')
                                                <td class="pt_10 pb_10">
                                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus role ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                @endif
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
