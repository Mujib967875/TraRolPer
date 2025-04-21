@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Buat Pengguna</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin_users') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Kembali</a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin_user_create_submit') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Foto *</label>
                                        <div><div class="upload-wrapper">
                                                    <div class="mini-drop-zone">
                                                      <input type="file" name="photo" name="fileInput1" class="file-input" accept="image/*">
                                                      <div class="drop-content">
                                                        <span class="drop-title"> <i class="upload-icon">📷</i> Pilih Foto</span>
                                                      </div>
                                                    </div>
                                                    <div class="preview-container" id="previewContainer1"></div>
                                                </div></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama *</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email *</label>
                                        <input type="text" class="form-control" name="email"
                                            value="{{ old('email') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Telpon *</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ old('phone') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Negara *</label>
                                        <input type="text" class="form-control" name="country"
                                            value="{{ old('country') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat *</label>
                                        <input type="text" class="form-control" name="address"
                                            value="{{ old('address') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Daerah *</label>
                                        <input type="text" class="form-control" name="state"
                                            value="{{ old('state') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kota *</label>
                                        <input type="text" class="form-control" name="city"
                                            value="{{ old('city') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Pos *</label>
                                        <input type="text" class="form-control" name="zip"
                                            value="{{ old('zip') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kata Sandi *</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status *</label>
                                        <select name="status" class="form-select">
                                            <option value="1">Active</option>
                                            <option value="0">Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"></label>
                                        <button type="submit" class="btn btn-primary">Buat</button>
                                    </div>
                            </div>
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
