@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-10 bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-3xl transform transition duration-300 hover:shadow-xl">

    <!-- Judul -->
    <h2 class="text-2xl md:text-3xl font-extrabold text-orange-600 mb-8 text-center">
      Konfirmasi Pembayaran
    </h2>

    <!-- Info Transaksi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mb-8 bg-gray-50 rounded-xl p-4 shadow-sm">
      <div><span class="font-semibold">📧 Email:</span> {{ $transaction->email ?? '-' }}</div>
      <div>
        <span class="font-semibold">💳 Status Pembayaran:</span>
        <span class="{{ $transaction->payment_status == 'paid' ? 'text-green-600 font-semibold' : 'text-orange-500 font-semibold' }}">
          {{ ucfirst($transaction->payment_status ?? 'Belum diketahui') }}
        </span>
      </div>
      <div><span class="font-semibold">🕒 Waktu Checkout:</span> {{ $transaction->checkout_time ?? '-' }}</div>
      <div><span class="font-semibold">🆔 ID Transaksi:</span> #{{ $transaction->id ?? '-' }}</div>
    </div>

    <!-- Daftar Tiket -->
    <div class="mb-8">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Tiket</h3>
      @forelse($details as $d)
        <div class="flex items-center gap-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-4 shadow-sm hover:shadow-md transition mb-3">
          <div class="flex-shrink-0 bg-orange-500 text-white rounded-full p-3 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405M19 13V7a2 2 0 00-2-2h-3.172a2 2 0 01-1.414-.586l-.828-.828A2 2 0 009.172 3H6a2 2 0 00-2 2v6l-1.405 1.405A2 2 0 002 13v4a2 2 0 002 2h5" />
            </svg>
          </div>
          <div>
            <p class="font-semibold text-gray-900">{{ $d->name ?? 'Tanpa Nama' }}</p>
            <p class="text-sm text-gray-600">📱 {{ $d->phone_number ?? '-' }}</p>
          </div>
        </div>
      @empty
        <p class="text-gray-500 italic">Tidak ada data tiket ditemukan.</p>
      @endforelse
    </div>

    <!-- Ringkasan Harga -->
    <div class="text-sm mb-8 border-t pt-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
      <div class="flex justify-between">
        <span>Harga per Tiket</span>
        <span><strong>Rp{{ number_format($hargaTiket, 0, ',', '.') }}</strong></span>
      </div>
      <div class="flex justify-between mt-1">
        <span>Jumlah Tiket</span>
        <span><strong>{{ count($details) }}</strong></span>
      </div>
      <div class="flex justify-between mt-3 text-base font-semibold text-orange-600 border-t pt-2">
        <span>Total Bayar</span>
        <span>Rp{{ number_format($totalBayar, 0, ',', '.') }}</span>
      </div>
    </div>

    <!-- Pesan Error -->
    @if(isset($errorMessage))
      <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm" role="alert">
        <strong class="font-bold">Info:</strong>
        <span class="block sm:inline">{{ $errorMessage }}</span>
      </div>
    @endif

    <!-- Tombol Aksi -->
    @if($transaction->payment_status == 'unpaid')
    <div class="flex justify-end gap-3 mt-4">
      <!-- Batalkan -->
      <form action="{{ route('ticket.cancel', $transaction->id) }}" method="POST">
        @csrf
        <button type="submit" 
          class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg shadow-sm hover:bg-gray-300 transition text-sm">
          Batalkan
        </button>
      </form>

      <!-- Bayar Sekarang -->
      <form action="{{ route('ticket.pay', $transaction->id) }}" method="POST">
        @csrf
        <button type="submit" 
          class="px-5 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 text-white font-semibold rounded-lg shadow-md transition transform hover:scale-105 text-sm">
          Bayar Sekarang
        </button>
      </form>
    </div>
    @endif

  </div>
</div>
@endsection
