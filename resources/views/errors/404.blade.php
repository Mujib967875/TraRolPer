<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oops! Halaman tidak ditemukan.</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    nav {
      background-color: #f8f9fa;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        transition: color 0.3s ease, transform 0.2s ease;
        padding: 8px 15px;
        border-radius: 8px;
    }

    .navbar-nav .nav-link:hover {
        color: #00aeef !important;
        transform: translateY(-2px);
        background-color: #f0f0f0;
    }

    .error-section {
      text-align: center;
      padding: 60px 20px;
    }

    .error-section img {
      max-width: 300px;
      margin-bottom: 30px;
    }

    .error-section h1 {
      font-size: 4rem;
      color: #343a40;
    }

    .error-section p {
      font-size: 1.2rem;
      color: #6c757d;
      margin-bottom: 30px;
    }

    .btn-back {
      padding: 10px 25px;
      font-size: 1rem;
      border-radius: 25px;
    }

    .social-links {
      margin-top: 40px;
    }

    .social-links a {
      color: #343a40;
      margin: 0 10px;
      font-size: 1.5rem;
      transition: color 0.3s ease;
    }

    .social-links a:hover {
      color: #343a40;
    }
  </style>
</head>
<body>

    @php
        $setting = App\Models\Setting::where('id', 1)->first();
    @endphp

  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('uploads/'.$setting->logo) }}" height="40px">
        </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link text-dark d-flex align-items-center gap-2" href="{{ route('about') }}">
              <i class="fas fa-info-circle"></i> Tentang Kami
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark d-flex align-items-center gap-2" href="{{ route('contact') }}">
              <i class="fas fa-phone-alt"></i> Hubungi Kami
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container error-section">
    <img src="{{ asset('uploads/404.svg') }}" alt="404 illustration" style="height: 380px;">
    <h1 class="mb-5">Oops! 404</h1>
    <p class="tect-danger mb-5">Halaman ini tidak tersedia. Silakan kembali ke halaman utama untuk melanjutkan.</p>

    <a href="{{ route('home') }}" class="btn btn-primary btn-back">
      <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
    </a>

    <div class="social-links mt-5">
      <a href="{{ $setting->facebook }}"><i class="fab fa-facebook"></i></a>
      <a href="{{ $setting->twitter }}"><i class="fab fa-twitter"></i></a>
      <a href="{{ $setting->instagram }}"><i class="fab fa-instagram"></i></a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
