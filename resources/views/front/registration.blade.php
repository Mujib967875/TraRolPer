@extends('front.layout.master')

@section('main_content')
@php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Buat Akun Baru</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Buat Akun Baru</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content pt_70 pb_70">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                    <div class="login-form">
                        <form action="{{ route('registration_submit') }}" method="post">
                            @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Nama *</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Alamat Email *</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-3">
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

                        {{-- <div class="mb-3">
                            <label for="" class="form-label">Kata Sandi *</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Ulangi Kata Sandi *</label>
                            <input type="password" class="form-control" name="retype_password">
                        </div> --}}

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website">
                                Buat Akun
                            </button>
                        </div>
                    </form>
                        <div class="mb-3">
                            <a href="{{ route('login') }}" class="primary-color">Sudah Punya Akun? Login Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
