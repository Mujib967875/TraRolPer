<div class="navbar-area" id="stickymenu">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('uploads/'.$setting->logo) }}" alt="">
        </a>
    </div>

    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('uploads/'.$setting->logo) }}" alt="">
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                        </li>
                        <li class="nav-item {{ Route::is('about') ? 'active' : '' }}">
                            <a href="{{ route('about') }}" class="nav-link">Tentang</a>
                        </li>
                        <li class="nav-item {{ Route::is('destinations') ? 'active' : '' }}">
                            <a href="{{ route('destinations') }}" class="nav-link">Destinasi</a>
                        </li>
                        <li class="nav-item {{ Route::is('packages') ? 'active' : '' }}">
                            <a href="{{ route('packages') }}" class="nav-link">Paket</a>
                        </li>
                        <li class="nav-item {{ Route::is('team_members') ? 'active' : '' }}">
                        <a href="{{ route('team_members') }}" class="nav-link">Tim</a>
                        </li>
                        <li class="nav-item {{ Route::is('faq') ? 'active' : '' }}">
                            <a href="{{ route('faq') }}" class="nav-link">Pertanyaan</a>
                        </li>
                        <li class="nav-item {{ Route::is('blog') ? 'active' : '' }}">
                            <a href="{{ route('blog') }}" class="nav-link">Blog</a>
                        </li>
                        <li class="nav-item {{ Route::is('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact') }}" class="nav-link">Kontak Kami</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
