@extends('layouts.app')

@section('title', 'RSCTicket')

@section('content')
<div class="px-4 py-8 sm:px-6 lg:px-8">

  <!-- 🔹 SLIDER BANNER -->
  <div class="mb-12">
    <div class="swiper mySwiper rounded-lg overflow-hidden">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
          <img src="{{ asset('slider1.jpg') }}" class="w-full object-cover h-48 sm:h-64 md:h-80 lg:h-96" alt="Banner 1">
        </div>
        <!-- Slide 2 -->
        <div class="swiper-slide">
          <img src="{{ asset('slider2.jpg') }}" class="w-full object-cover h-48 sm:h-64 md:h-80 lg:h-96" alt="Banner 2">
        </div>
        <!-- Slide 3 -->
        <div class="swiper-slide">
          <img src="{{ asset('slider3.jpg') }}" class="w-full object-cover h-48 sm:h-64 md:h-80 lg:h-96" alt="Banner 3">
        </div>
      </div>

      <!-- Optional Pagination -->
      <div class="swiper-pagination mt-2"></div>
    </div>
  </div>
  <!-- 🔸 CTA Banner: Cara Memesan -->
  <div class="mb-12">
    <a href="{{ url('/cara-memesan') }}">
      <img src="{{ asset('banner-cara-memesan.png') }}" alt="Cara Memesan Tiket" class="rounded-lg w-full object-cover shadow-md hover:opacity-90 transition duration-300">
    </a>
  </div>

  <!-- 🔹 UPCOMING SHOWS -->
  <h1 class="text-2xl sm:text-3xl font-extrabold text-center mb-8 text-gray-900">Upcoming Shows</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 justify-items-center">
    <!-- Card 1 -->
    <div class="bg-white rounded-xl shadow-md w-full max-w-sm overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover">
      <div class="p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">[Early Bird] Negative Mental Atittude</h2>
        <p class="text-gray-600 mb-1">📍 Kediri</p>
        <p class="text-gray-600 mb-4">🗓️ Oktober 2025</p>
        <a href="{{ route('ticket.create') }}" class="inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition">
          Beli Tiket
        </a>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="relative bg-white rounded-xl shadow-md w-full max-w-sm overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover brightness-50">
      <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-white text-lg sm:text-2xl font-bold bg-black bg-opacity-50 px-3 py-1 sm:px-4 sm:py-2 rounded">COMING SOON</span>
      </div>
      <div class="p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">[Presale-1] Negative Mental Atittude</h2>
        <p class="text-gray-600 mb-1">📍 Lokasi</p>
        <p class="text-gray-600 mb-4">🗓️ Tanggal</p>
        <a href="#" class="inline-block w-full text-center bg-gray-400 cursor-not-allowed text-white font-semibold px-4 py-2 rounded-md transition" disabled>
          Belum Tersedia
        </a>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="relative bg-white rounded-xl shadow-md w-full max-w-sm overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover brightness-50">
      <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-white text-lg sm:text-2xl font-bold bg-black bg-opacity-50 px-3 py-1 sm:px-4 sm:py-2 rounded">COMING SOON</span>
      </div>
      <div class="p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">[Presale 2] Negative Mental Atittade</h2>
        <p class="text-gray-600 mb-1">📍 Lokasi</p>
        <p class="text-gray-600 mb-4">🗓️ Tanggal</p>
        <a href="#" class="inline-block w-full text-center bg-gray-400 cursor-not-allowed text-white font-semibold px-4 py-2 rounded-md transition" disabled>
          Belum Tersedia
        </a>
      </div>
    </div>

    <!-- Tambahan card jika perlu -->
  </div>

</div>

<!-- Swiper Init Script -->
<script>
  const swiper = new Swiper('.mySwiper', {
    loop: true,
    autoplay: {
      delay: 4000,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  });
</script>
@endsection
