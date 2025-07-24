@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gray-50 text-gray-800">
  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-3xl">

    <!-- Judul -->
    <h2 class="text-2xl md:text-3xl font-extrabold text-center text-blue-700 mb-8">Detail Pembayaran</h2>

    <!-- Info Transaksi -->
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

    <!-- QR Code -->
    <div class="text-center bg-gray-100 p-6 rounded-xl shadow-inner mb-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">🧾 Silakan Scan QRIS untuk Membayar</h3>

      @if(isset($qrURL))
        <img src="{{ $qrURL }}" alt="QR Code Pembayaran" class="mx-auto w-64 h-64 rounded-lg shadow-md border border-gray-300 mb-4">
        <p class="text-gray-600 text-sm mb-2">Batas waktu pembayaran:</p>
        <p class="text-red-600 font-semibold text-lg" id="countdown">Memuat waktu...</p>
      @else
        <p class="italic text-gray-500">QR Code tidak tersedia. Silakan coba lagi nanti.</p>
      @endif
    </div>

    <!-- Tombol Refresh -->
    <div class="text-center">
      <a href="{{ route('ticket.payment', ['id' => $transaction->id]) }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">🔄 Cek Status Pembayaran</a>
    </div>

    <!-- Error Message -->
    @if(isset($errorMessage))
      <div class="mt-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline">{{ $errorMessage }}</span>
      </div>
    @endif

  </div>
</div>

<!-- Script Countdown -->
@if(isset($expiryTime))
<script>
  const expiry = new Date("{{ $expiryTime }}").getTime();
  const countdown = document.getElementById("countdown");

  const interval = setInterval(() => {
    const now = new Date().getTime();
    const distance = expiry - now;

    if (distance <= 0) {
      clearInterval(interval);
      countdown.innerHTML = "⏱️ Waktu pembayaran telah habis";
      return;
    }

    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    countdown.innerHTML = `${minutes} menit ${seconds} detik`;
  }, 1000);
</script>
@endif
@endsection
