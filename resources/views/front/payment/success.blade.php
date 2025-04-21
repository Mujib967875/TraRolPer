@extends('front.layout.master')

@section('main_content')
    @php
        $setting = App\Models\Setting::where('id', 1)->first();
    @endphp

    <style>
        .jsc {
            justify-self: center;
            text-align: center;
            margin: 50px;
        }

        .icon {
            justify-self: center;
            font-size: 100px;
            color: #5CE65C;
        }
    </style>

    <div class="page-top" style="background-image: url({{ asset('uploads/' . $setting->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Pembayaran Berhasil!</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Pembayaran</a></li>
                            <li class="breadcrumb-item active">Pembayaran Berhasil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="jsc">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Pembayaran Berhasil!</h1>
                    <div class="icon mt-5">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a class="btn btn-primary mt-5 mb-3" href="{{ route('home') }}">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = localStorage.getItem('toastr_message');
            const type = localStorage.getItem('toastr_type');

            if (message) {
                if (type === 'success') {
                    toastr.success(message);
                } else if (type === 'error') {
                    toastr.error(message);
                } else if (type === 'info') {
                    toastr.info(message);
                } else if (type === 'warning') {
                    toastr.warning(message);
                }

                localStorage.removeItem('toastr_message');
                localStorage.removeItem('toastr_type');
            }
        });
    </script>
@endsection
