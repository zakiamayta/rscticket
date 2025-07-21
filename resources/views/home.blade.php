@extends('layouts.app')

@section('title', 'Konser Musik Nusantara 2025')

@section('content')
<div class="flex flex-col justify-center items-center px-4 py-10">
  <!-- Poster -->
  <div class="max-w-md w-full mb-6">
    <img src="{{ asset('poster.jpeg') }}" alt="Poster Konser" class="rounded-xl shadow-lg w-full aspect-square object-cover">
  </div>

  <!-- Informasi Konser -->
  <div class="text-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Negative Mental Atittude</h1>
    <p class="text-lg text-gray-600">📍 Kediri</p>
    <p class="text-lg text-gray-600">🗓️ Oktober 2025</p>
  </div>

  <!-- Tombol Beli Tiket -->
  <a href="{{ route('ticket.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full shadow-md transition">
    🎟️ Beli Tiket Sekarang
  </a>

  <!-- CP Info -->
  <div class="mt-8 text-center text-sm text-gray-500">
    <p>📞 Hubungi CP: 0812-3456-7890 (WhatsApp)</p>
    <p>✉️ Email: tiket@nusantaramusik.id</p>
  </div>
</div>
@endsection
