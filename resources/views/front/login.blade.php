@extends('front.layout.master')

@section('main_content')
@php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Login</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Login</li>
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
                    <form action="{{ route('login_submit') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Alamat Email</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                        </div>
                        {{-- <div class="mb-3">
                            <label for="" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control" name="password">
                        </div> --}}
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
                            <button type="submit" class="btn btn-primary bg-website">
                                Login
                            </button>
                            <a href="{{ route('forget_password') }}" class="primary-color">Lupa Kata Sandi?</a>
                        </div>
                    </form>
                    <div class="mb-3">
                        <a href="{{ route('registration') }}" class="primary-color">Belum Punya Akun? Daftar Sekarang!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
