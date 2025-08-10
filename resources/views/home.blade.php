@extends('layouts.app')

@section('title', 'RSCTicket')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-8">

  <!-- 🔹 SLIDER BANNER -->
  <div class="mb-12" data-aos="fade-up" data-aos-duration="800">
    <div class="swiper mySwiper rounded-xl overflow-hidden shadow-lg">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide relative group">
          <img src="{{ asset('slider1.jpg') }}" class="w-full h-60 sm:h-72 md:h-[22rem] lg:h-[26rem] object-cover transition-transform duration-500 group-hover:scale-105" alt="Banner 1">
          <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
            <h2 class="text-white text-2xl sm:text-3xl font-bold mb-2">Negatifa</h2>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide relative group">
          <img src="{{ asset('slider2.jpg') }}" class="w-full h-60 sm:h-72 md:h-[22rem] lg:h-[26rem] object-cover transition-transform duration-500 group-hover:scale-105" alt="Banner 2">
          <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
            <h2 class="text-white text-2xl sm:text-3xl font-bold mb-2">Evilbreed</h2>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide relative group">
          <img src="{{ asset('slider3.jpg') }}" class="w-full h-60 sm:h-72 md:h-[22rem] lg:h-[26rem] object-cover transition-transform duration-500 group-hover:scale-105" alt="Banner 3">
          <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
            <h2 class="text-white text-2xl sm:text-3xl font-bold mb-2">Brake</h2>
          </div>
        </div>
      </div>
      <div class="swiper-pagination mt-2"></div>
    </div>
  </div>
  <!-- 🔸 CTA: Cara Memesan -->
  <div class="mb-12" data-aos="fade-up" data-aos-duration="900" data-aos-delay="200">
    <a href="{{ url('/cara-memesan') }}" class="block transform transition duration-500 hover:scale-[1.02]">
      <img src="{{ asset('banner-cara-memesan.png') }}" 
          alt="Cara Memesan Tiket" 
          class="rounded-xl w-full object-cover shadow-lg hover:shadow-2xl">
    </a>
  </div>


  <!-- 🔹 UPCOMING SHOWS -->
  <div id="upcoming-events" class="mb-6" data-aos="fade-right" data-aos-duration="800">
      <h1 class="text-left text-2xl sm:text-3xl font-extrabold text-gray-900 flex items-center gap-2">
        <i class="fa-solid fa-calendar-days text-orange-500"></i>
        Upcoming Shows
      </h1>
      <div class="h-1 w-24 bg-orange-500 mt-2 rounded"></div>
  </div>

  <!-- Grid Event Card Dinamis -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 justify-items-center">
    @forelse($events as $event)
      <div class="event-card bg-white rounded-xl shadow-md w-full max-w-sm overflow-hidden transform transition duration-300 hover:shadow-xl hover:-translate-y-1"
           data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full aspect-square object-cover">
        <div class="p-4 sm:p-6">
          <h2 class="event-title text-lg sm:text-xl font-bold text-gray-900 mb-1">{{ $event->title }}</h2>

          <p class="text-gray-600 mb-1">📍 {{ $event->location }}</p>
          <p class="text-gray-600 mb-4">🗓️ {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</p>

          <a href="{{ route('ticket.form', ['event_id' => $event->id]) }}"
            class="inline-block bg-gradient-to-r from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition transform hover:scale-105">
            More Info
          </a>
        </div>
      </div>
    @empty
      <p class="text-gray-500 col-span-full" data-aos="fade-up">Belum ada event yang tersedia.</p>
    @endforelse
  </div>

</div>

<!-- Swiper Init Script -->
<script>
  const swiper = new Swiper('.mySwiper', {
    loop: true,
    slidesPerView: 2,
    spaceBetween: 16,
    slidesPerGroup: 1,
    autoplay: {
      delay: 4000,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      0: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
    }
  });
</script>

<!-- AOS Init -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    once: true,
    offset: 50
  });
</script>
@endsection
