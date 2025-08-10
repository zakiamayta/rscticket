@extends('layouts.app')

@section('title', 'Pembelian Sukses')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-2xl text-center border border-gray-200">

    <!-- Ikon Sukses -->
    <div class="flex justify-center mb-6">
      <div class="bg-orange-100 p-4 rounded-full shadow-inner animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <!-- Judul -->
    <h2 class="text-3xl font-extrabold text-orange-600 mb-2">
      Pembelian Berhasil
    </h2>
    <p class="text-gray-500 mb-6">Detail transaksi Anda telah tercatat di sistem kami.</p>

    <!-- Info Transaksi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mb-8 text-left bg-gray-50 p-4 rounded-lg">
      <div><span class="font-semibold">Email:</span> {{ $transaction->email ?? '-' }}</div>
      <div>
        <span class="font-semibold">Status Pembayaran:</span> 
        <span class="{{ $transaction->payment_status == 'paid' ? 'text-green-600 font-semibold' : 'text-orange-500 font-semibold' }}">
          {{ ucfirst($transaction->payment_status ?? '-') }}
        </span>
      </div>
      <div><span class="font-semibold">Waktu Checkout:</span> {{ $transaction->checkout_time ?? '-' }}</div>
      <div><span class="font-semibold">ID Transaksi:</span> #{{ $transaction->id ?? '-' }}</div>
    </div>

    <!-- Pesan -->
    <div class="text-center mb-6">
      <p class="text-gray-700 text-base leading-relaxed">
        Terima kasih telah memesan tiket!<br>
        Anda akan menerima <strong>E-Ticket</strong> melalui email 
        <span class="text-orange-500 font-semibold">setelah pembayaran terverifikasi</span>.
      </p>
    </div>

    <!-- Peringatan Email -->
    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-8 text-left rounded-md">
      <p class="text-orange-800 text-sm">
        <strong>Catatan:</strong> Jika dalam waktu <strong>24 jam</strong> email berisi E-Ticket belum Anda terima, 
        silakan hubungi <span class="font-semibold text-orange-700">kontak person kami</span> di 
        <a href="https://wa.me/6285230088828" target="_blank" class="underline hover:text-yellow-900">+6285-2300-882-8</a>.
      </p>
    </div>

    <!-- Tombol Selesai -->
    <div class="flex justify-center">
      <a href="{{ url('/') }}" 
         class="inline-block bg-gradient-to-r from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition transform hover:scale-105">
        Selesai
      </a>
    </div>

  </div>
</div>
@endsection
