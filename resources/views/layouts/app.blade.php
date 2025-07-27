<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'RSCTix')</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- icon sampah -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Swiper -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
    }
  </style>

  @stack('styles')
</head>

<body class="text-gray-800">
  @include('layouts.navbar')

  <!-- Konten -->
  <main class="min-h-screen">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white pt-8 pb-4 mt-12">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
      
      <!-- Logo & Deskripsi -->
      <div>
        <h3 class="text-xl font-bold mb-2">RSCTix</h3>
        <p class="text-sm text-gray-400 mb-2">Platform pembelian tiket konser terpercaya dan mudah digunakan.</p>
        <p class="text-sm text-gray-400">📧 <a href="mailto:support@rsctix.id" class="hover:text-white">support@rsctix.id</a></p>
        <p class="text-sm text-gray-400">📞 <a href="tel:+6281234567890" class="hover:text-white">+62 812-3456-7890</a></p>
      </div>

      <!-- Navigasi -->
      <div>
        <h4 class="font-semibold mb-2">Informasi</h4>
        <ul class="space-y-1 text-sm text-gray-400">
          <li><a href="{{ url('/about-us') }}" class="hover:text-white">Tentang Kami</a></li>
          <li><a href="{{ url('/privacy-policy') }}" class="hover:text-white">Kebijakan Privasi</a></li>
          <li><a href="{{ url('/faq') }}" class="hover:text-white">FAQ</a></li>
          <li><a href="{{ url('/terms') }}" class="hover:text-white">Syarat & Ketentuan</a></li>
          <li><a href="{{ url('/contact') }}" class="hover:text-white">Kontak</a></li>
        </ul>
      </div>

      <!-- Sosial Media -->
      <div>
        <h4 class="font-semibold mb-2">Ikuti Kami</h4>
        <div class="flex space-x-4">
          <a href="https://instagram.com/rupasuaracahaya" target="_blank" class="hover:text-orange-400">
            <i class="fab fa-instagram text-xl"></i>
          </a>
          <a href="https://twitter.com/rupasuaracahaya" target="_blank" class="hover:text-orange-400">
            <i class="fab fa-twitter text-xl"></i>
          </a>
          <a href="https://facebook.com/rupasuaracahaya" target="_blank" class="hover:text-orange-400">
            <i class="fab fa-facebook text-xl"></i>
          </a>
        </div>
      </div>

    </div>

    <div class="text-center text-sm text-gray-500 mt-8 border-t border-gray-700 pt-4">
      &copy; {{ date('Y') }} RSCTix. All rights reserved.
    </div>
  </footer>


  @stack('scripts')
</body>
</html>
