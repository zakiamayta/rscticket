@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gray-50 text-gray-800">
  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-3xl">

    <h2 class="text-2xl md:text-3xl font-extrabold text-center text-blue-700 mb-8">Detail Pembayaran</h2>

    <!-- Status Pembayaran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mb-6">
      <div><span class="font-semibold">📧 Email:</span> {{ $transaction->email ?? 'Tidak tersedia' }}</div>
      <div><span class="font-semibold">💳 Status Pembayaran:</span> {{ $transaction->payment_status ?? 'Belum diketahui' }}</div>
      <div><span class="font-semibold">🕒 Waktu Checkout:</span> {{ $transaction->checkout_time ?? '-' }}</div>
    </div>

    <!-- Daftar Tiket -->
    <div class="mb-8">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">👥 Daftar Tiket</h3>
      @forelse($details as $d)
        <div class="bg-gray-100 rounded-lg p-4 shadow-sm mb-3">
          <p class="font-semibold text-gray-900">{{ $d->name ?? 'Tanpa Nama' }}</p>
          <p class="text-sm text-gray-700">NIK: {{ $d->nik ?? '-' }}</p>
          <p class="text-sm text-gray-700">No. Telepon: {{ $d->phone_number ?? 'Tidak ada nomor' }}</p>
        </div>
      @empty
        <p class="text-gray-500 italic">Tidak ada data tiket ditemukan.</p>
      @endforelse
    </div>

    <!-- Harga -->
    <div class="text-sm mb-8">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">💰 Informasi Pembayaran</h3>
      <p>🎟️ Harga per Tiket: <strong>Rp{{ number_format($hargaTiket, 0, ',', '.') }}</strong></p>
      <p>👤 Jumlah Tiket: <strong>{{ count($details) }}</strong></p>
      <p class="text-blue-700 font-bold text-lg mt-2">Total Bayar: Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
    </div>

    <!-- QRIS Placeholder -->
    <div class="text-center bg-gray-100 p-6 rounded-xl shadow-inner text-gray-600 italic">
      <p><strong>QRIS dan detail pembayaran Tripay akan muncul di sini nanti.</strong></p>
    </div>

  </div>
</div>
@endsection
