@extends('front.layout.master')

@section('main_content')
    @php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dashboard</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
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
                    <h3 class="mb_20">Hello, {{ Auth::guard('web')->user()->name }}</h3>
                    <div class="row box-items">
                        <div class="col-md-4">
                            <div class="box1">
                                <h4>{{ $total_completed_orders }}</h4>
                                <p>Pesanan Selesai</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box2">
                                <h4>{{ $total_pending_orders }}</h4>
                                <p>Pesanan Tertunda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
