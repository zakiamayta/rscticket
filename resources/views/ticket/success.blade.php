@extends('layouts.app')

@section('title', 'Pembelian Sukses')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-10 bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-2xl text-center transform transition duration-300 hover:shadow-xl">

    <!-- Ikon Sukses -->
    <div class="flex justify-center mb-6 animate-bounce">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-orange-500 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <!-- Judul -->
    <h2 class="text-2xl sm:text-3xl font-extrabold text-orange-600 mb-6">
      Pembelian Berhasil
    </h2>

    <!-- Info Transaksi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mb-6 text-left">
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
    <div class="text-center mb-8">
      <p class="text-gray-700 text-base leading-relaxed">
        Terima kasih telah memesan tiket!<br>
        Anda akan menerima <strong>E-Ticket</strong> melalui email <span class="text-orange-500">setelah pembayaran terverifikasi</span>.
      </p>
    </div>

    <!-- Tombol Selesai -->
    <div class="flex justify-center">
      <a href="{{ url('/') }}" 
         class="inline-block bg-gradient-to-r from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
        Selesai
      </a>
    </div>

  </div>
</div>
@endsection
