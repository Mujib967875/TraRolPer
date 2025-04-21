@extends('front.layout.master')

@section('main_content')
    @php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Profil</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content user-panel pt_70 pb_70">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-12">
                    <div class="card">
                        @include('user.sidebar')
                    </div>
                </div>
                <div class="col-lg-9 col-md-12">
                    <form action="{{ route('user_profile_submit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="col-md-4">
                                <label for="">Foto</label>
                                <div class="form-group">
                                    @if (Auth::guard('web')->user()->photo != '')
                                    <img src="{{ asset('uploads/'.Auth::guard('web')->user()->photo) }}" alt="" class="user-photo">
                                    @else
                                    <img src="{{ asset('uploads/default.png') }}" alt="" class="user-photo">
                                    @endif
                                </div>
                            </div>
                        </div>
                            <div class="col-md-6 mb-3">
                                <div class="col-md-4">
                                    <label for="">Ganti Foto</label>
                                        <div class="form-group">
                                            <div class="upload-wrapper">
                                            <div class="mini-drop-zone">
                                            <input type="file" name="photo" class="file-input" accept="image/*">
                                            <div class="drop-content">
                                                <span class="drop-title"> <i class="upload-icon">📷</i> Pilih Foto</span>
                                            </div>
                                            </div>
                                            <div class="preview-container" id="previewContainer"></div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Nama Lengkap *</label>
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" value="{{ Auth::guard('web')->user()->name }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Alamat Email *</label>
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control" value="{{ Auth::guard('web')->user()->email }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">No Telpon *</label>
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control" value="{{ Auth::guard('web')->user()->phone }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Negara *</label>
                                <div class="form-group">
                                    <input type="text" name="country" class="form-control" value="{{ Auth::guard('web')->user()->country }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Alamat *</label>
                                <div class="form-group">
                                    <input type="text" name="address" class="form-control" value="{{ Auth::guard('web')->user()->address }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Wilayah *</label>
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control" value="{{ Auth::guard('web')->user()->state }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Kota *</label>
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" value="{{ Auth::guard('web')->user()->city }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Kode Pos *</label>
                                <div class="form-group">
                                    <input type="text" name="zip" class="form-control" value="{{ Auth::guard('web')->user()->zip }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group position-relative">
                                    <label for="password" class="form-label">Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control password-field" id="password" name="password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group position-relative">
                                    <label for="retype_password" class="form-label">Konfirmasi Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control password-field" id="retype_password" name="retype_password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input name="form_update" type="submit" class="btn btn-primary" value="Perbarui">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
