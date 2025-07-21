@extends('layouts.app')

@section('title', 'Konser Musik Nusantara 2025')

@section('content')
<div class="px-4 py-10">
  <h1 class="text-3xl font-extrabold text-center mb-10 text-gray-900">Event Konser Musik</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center">
    <!-- Card 1 -->
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover">
      <div class="p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">[Early Bird] Negative Mental Atittude</h2>
        <p class="text-gray-600 mb-1">📍 Kediri</p>
        <p class="text-gray-600 mb-4">🗓️ Oktober 2025</p>
        <a href="{{ route('ticket.create') }}" class="inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition">
          Beli Tiket
        </a>
      </div>
    </div>

  <!-- Card 2 (Coming Soon) -->
  <div class="relative bg-white rounded-xl shadow-lg max-w-sm w-full overflow-hidden">
    <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover brightness-50">
    <div class="absolute inset-0 flex items-center justify-center">
      <span class="text-white text-2xl font-bold bg-black bg-opacity-50 px-4 py-2 rounded">COMING SOON</span>
    </div>
    <div class="p-6">
      <h2 class="text-xl font-bold text-gray-900 mb-2">[Presale-1] Negative Mental Atittude</h2>
      <p class="text-gray-600 mb-1">📍 Lokasi</p>
      <p class="text-gray-600 mb-4">🗓️ Tanggal</p>
      <a href="#" class="inline-block w-full text-center bg-gray-400 cursor-not-allowed text-white font-semibold px-4 py-2 rounded-md transition" disabled>
        Belum Tersedia
      </a>
    </div>
  </div>

  <!-- Card 3 (Coming Soon) -->
  <div class="relative bg-white rounded-xl shadow-lg max-w-sm w-full overflow-hidden">
    <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover brightness-50">
    <div class="absolute inset-0 flex items-center justify-center">
      <span class="text-white text-2xl font-bold bg-black bg-opacity-50 px-4 py-2 rounded">COMING SOON</span>
    </div>
    <div class="p-6">
      <h2 class="text-xl font-bold text-gray-900 mb-2">[Presale 2] Negative Mental Atittude</h2>
      <p class="text-gray-600 mb-1">📍 Lokasi</p>
      <p class="text-gray-600 mb-4">🗓️ Tanggal</p>
      <a href="#" class="inline-block w-full text-center bg-gray-400 cursor-not-allowed text-white font-semibold px-4 py-2 rounded-md transition" disabled>
        Belum Tersedia
      </a>
    </div>
  </div>


    <!-- Card 4 -->
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full overflow-hidden">
      <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="w-full aspect-square object-cover">
      <div class="p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Judul Event Lain</h2>
        <p class="text-gray-600 mb-1">📍 Lokasi</p>
        <p class="text-gray-600 mb-4">🗓️ Tanggal</p>
        <a href="#" class="inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition">
          Beli Tiket
        </a>
      </div>
    </div>

  </div>

  <!-- CP Info -->
  <div class="mt-10 text-center text-sm text-gray-500">
    <p>📞 Hubungi CP: 0812-3456-7890 (WhatsApp)</p>
    <p>✉️ Email: tiket@nusantaramusik.id</p>
  </div>
</div>
@endsection
