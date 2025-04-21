<ul class="list-group list-group-flush">
    <li class="list-group-item {{ Route::is('user_dashboard') ? 'active' : '' }}">
        <a href="{{ route('user_dashboard') }}">Dashboard</a>
    </li>
    <li class="list-group-item {{ Route::is('user_booking') || Route::is('user/invoice/*') ? 'active' : '' }}">
        <a href="{{ route('user_booking') }}">Pemesanan</a>
    </li>
    <li class="list-group-item {{ Route::is('user_wishlist') ? 'active' : '' }}">
        <a href="{{ route('user_wishlist') }}">Wishlist</a>
    </li>
    <li class="list-group-item {{ Route::is('user_message') ? 'active' : '' }}">
        <a href="{{ route('user_message') }}">Obrolan</a>
    </li>
    <li class="list-group-item {{ Route::is('user_review') ? 'active' : '' }}">
        <a href="{{ route('user_review') }}">Ulasan</a>
    </li>
    <li class="list-group-item {{ Route::is('user_profile') ? 'active' : '' }}">
        <a href="{{ route('user_profile') }}">Ubah Profil</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('logout') }}">Keluar</a>
    </li>
</ul>
