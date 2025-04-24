@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Tambah Permission</h1>
                <div class="ml-auto">
                    <a href="{{ route('permissions.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('permissions.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Nama Permission *</label>
                                        <input type="text" name="name" class="form-control" placeholder="Contoh: post.create, user.edit" required>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Simpan Permission
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
