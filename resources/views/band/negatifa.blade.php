@extends('layouts.app')

@section('title', 'Profil Band Negatifa')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-10 bg-gray-50 min-h-screen">
  <div class="container mx-auto max-w-5xl">
    
    <!-- 🔹 Foto & Nama Band -->
    <div class="text-center mb-12">
      <img src="{{ asset('slider1.jpg') }}" alt="Negatifa" 
           class="w-full max-h-[400px] object-contain bg-black rounded-xl shadow-lg mb-6">
      <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Negatifa</h1>
      <p class="text-gray-700 max-w-3xl mx-auto leading-relaxed text-lg">
        Negatifa adalah unit powerviolence dengan sound yang heavy dan tertata rapi untuk sebuah band powerviolence. 
        Beranggotakan personil Masakre, Stride, Cryptical Death, dan Seringai. 
        Memainkan hardcore punk cepat dan pendek, dengan elemen sludge dan mosh part thrashy. 
        Liriknya membahas hal penting seperti: kenapa kalian menyebalkan, filsafat, dan alasan polsek bisa terbakar. 
        Cocok untuk penggemar Infest atau Dropdead. Satu hal pasti: mereka main keras dan singkat.
      </p>
    </div>

    <!-- 🔗 Instagram -->
    <div class="mt-8 mb-14 text-center">
      <a href="https://instagram.com/negatifa.violence" target="_blank" rel="noopener noreferrer" 
         class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 text-lg font-semibold transition transform hover:scale-105">
        <i class="fab fa-instagram text-2xl"></i>
        @negatifa.violence
      </a>
    </div>

    <!-- 🎟 EVENT MENDATANG -->
    <div class="mb-12">
      <h2 class="text-3xl font-extrabold text-gray-900 flex items-center gap-2">
        <i class="fa-solid fa-calendar-days text-orange-500"></i> Event Mendatang
      </h2>
      <div class="h-1 w-28 bg-orange-500 mt-2 rounded mb-6"></div>

      <div class="bg-white border shadow rounded-lg p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 hover:shadow-lg transition">
        <!-- Tanggal -->
        <div class="flex items-center gap-4">
          <div class="w-20 rounded-lg overflow-hidden shadow-sm border border-gray-300">
            <div class="bg-orange-500 text-white text-xs text-center font-bold py-1 tracking-wide uppercase rounded-t-lg">
              OCT
            </div>
            <div class="bg-white flex flex-col items-center justify-center py-2">
              <div class="text-3xl font-serif font-bold text-black leading-none">19</div>
              <div class="text-[10px] text-gray-500 mt-1">2025</div>
            </div>
          </div>

          <!-- Info -->
          <div>
            <h3 class="font-bold text-lg text-gray-900">Negative Mental Attitude</h3>
            <p class="text-gray-600 text-sm">Kediri</p>
            <p class="text-gray-500 text-sm italic">Sabtu, 19 Oktober 2025 • 19:00 WIB</p>
          </div>
        </div>

        <!-- Tombol Tiket -->
        <div class="text-end">
          <!-- <a href="{{ route('ticket.form', ['event_id' => 1]) }}" 
             class="inline-block bg-gradient-to-r from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition transform hover:scale-105">
            🎟 Beli Tiket
          </a> -->
        </div>
      </div>
    </div>

    <!-- 🎵 DENGARKAN DI SPOTIFY -->
    <div class="my-12">
      <h2 class="text-3xl font-extrabold text-gray-900 flex items-center gap-2">
        <i class="fa-brands fa-spotify text-green-500"></i> Dengarkan di Spotify
      </h2>
      <div class="h-1 w-28 bg-green-500 mt-2 rounded mb-6"></div>

      <div class="flex flex-col sm:flex-row gap-6">
        <iframe style="border-radius:12px" 
                src="https://open.spotify.com/embed/artist/4OtqLk2bIcV3OLbrnZkQ95?utm_source=generator" 
                width="100%" height="352" frameBorder="0" allowfullscreen="" 
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy">
        </iframe>
      </div>
    </div>

  </div>
</div>
@endsection
