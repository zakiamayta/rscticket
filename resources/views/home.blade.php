@extends('layouts.app')

@section('title', 'RSCTicket')

@section('content')
<div class="px-4 py-8 sm:px-6 lg:px-8">

  <!-- 🔹 SLIDER BANNER -->
  <div class="mb-12">
    <div class="swiper mySwiper rounded-lg overflow-hidden">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img src="{{ asset('slider1.jpg') }}" class="w-full object-cover h-60 sm:h-72 md:h-[22rem] lg:h-[26rem]" alt="Banner 1">
        </div>
        <div class="swiper-slide">
          <img src="{{ asset('slider2.jpg') }}" class="w-full object-cover h-60 sm:h-72 md:h-[22rem] lg:h-[26rem]" alt="Banner 2">
        </div>
        <div class="swiper-slide">
          <img src="{{ asset('slider3.jpg') }}" class="w-full object-cover h-60 sm:h-72 md:h-[22rem] lg:h-[26rem]" alt="Banner 3">
        </div>
        <div class="swiper-slide">
          <img src="{{ asset('slider4.jpg') }}" class="w-full object-cover h-60 sm:h-72 md:h-[22rem] lg:h-[26rem]" alt="Banner 4">
        </div>
      </div>
      <div class="swiper-pagination mt-2"></div>
    </div>
  </div>

  <!-- 🔸 CTA: Cara Memesan -->
  <div class="mb-12">
    <a href="{{ url('/cara-memesan') }}">
      <img src="{{ asset('banner-cara-memesan.png') }}" alt="Cara Memesan Tiket" class="rounded-lg w-full object-cover shadow-md hover:opacity-90 transition duration-300">
    </a>
  </div>

  <!-- 🔹 UPCOMING SHOWS -->
  <div class="mb-6">
    <h1 class="text-left text-2xl sm:text-3xl font-extrabold text-gray-900 flex items-center gap-2">
      <i class="fa-solid fa-calendar-days text-orange-500"></i>
      Upcoming Shows
    </h1>
    <div class="h-1 w-24 bg-orange-500 mt-2 rounded"></div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 justify-items-center">
    <!-- Card 1 -->
    <div class="bg-white rounded-xl shadow-md w-full max-w-sm overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover">
      <div class="p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Early Bird: Negative Mental Attitude</h2>
        <p class="text-gray-600 mb-1">📍 Kediri</p>
        <p class="text-gray-600 mb-4">🗓️ Oktober 2025</p>
        <a href="{{ route('ticket.create') }}" class="inline-block w-full text-center bg-orange-600 hover:bg-orange-700 text-white font-semibold px-4 py-2 rounded-md transition">
          Beli Tiket
        </a>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="relative bg-white rounded-xl shadow-md w-full max-w-sm overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover brightness-50">
      <div class="absolute inset-0 flex items-center justify-center"></div>
      <div class="p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">[COMING SOON] Presale 1: Negative Mental Attitude</h2>
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
      <div class="absolute inset-0 flex items-center justify-center"></div>
      <div class="p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">[COMING SOON] Presale 2: Negative Mental Attitude</h2>
        <p class="text-gray-600 mb-1">📍 Lokasi</p>
        <p class="text-gray-600 mb-4">🗓️ Tanggal</p>
        <a href="#" class="inline-block w-full text-center bg-gray-400 cursor-not-allowed text-white font-semibold px-4 py-2 rounded-md transition" disabled>
          Belum Tersedia
        </a>
      </div>
    </div>
  </div>
    <!-- 🔊 DENGARKAN DI SPOTIFY -->
  <div class="my-12">
    <h1 class="text-left text-2xl sm:text-3xl font-extrabold text-gray-900 flex items-center gap-2">
      <i class="fa-brands fa-spotify text-green-500"></i>
      Dengarkan di Spotify
    </h1>
    <div class="h-1 w-24 bg-green-500 mt-2 rounded mb-4"></div>

    <div class="flex flex-col sm:flex-row gap-6">
      <!-- Contoh Embed Lagu -->
    <iframe data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/artist/4OtqLk2bIcV3OLbrnZkQ95?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>

      <!-- Bisa tambah playlist atau album juga -->
    </div>
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
