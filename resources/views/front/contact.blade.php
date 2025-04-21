@extends('front.layout.master')

@section('main_content')
    @php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Kontak Kami</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Kontak Kami</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact pt_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="contact-form pb_70">
                        <form action="{{ route('contact_submit') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input name="name" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Email</label>
                                <input name="email" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pesan</label>
                                <textarea name="comment" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="map">
                        {!! $contact_item->map_code !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
