<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Halaman Pembayaran</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Wrapper -->
  <div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="bg-white shadow-md rounded-xl p-8 w-full max-w-3xl">

      <h2 class="text-2xl font-bold text-center mb-6 text-blue-700">Detail Pembayaran</h2>

      <!-- Status -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm text-gray-700">
        <p><strong>Email:</strong> {{ $transaction->email ?? 'Tidak tersedia' }}</p>
        <p><strong>Status Pembayaran:</strong> {{ $transaction->payment_status ?? 'Belum diketahui' }}</p>
        <p><strong>Waktu Checkout:</strong> {{ $transaction->checkout_time ?? '-' }}</p>
      </div>

      <!-- Tiket -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Daftar Tiket</h3>
        <ul class="space-y-4">
          @forelse($details as $d)
            <li class="bg-gray-100 p-4 rounded-md shadow-sm">
              <p class="font-semibold">{{ $d->name ?? 'Tanpa Nama' }}</p>
              <p>NIK: {{ $d->nik ?? '-' }}</p>
              <p>No. Telepon: {{ $d->phone_number ?? 'Tidak ada nomor' }}</p>
            </li>
          @empty
            <li class="text-gray-500">Tidak ada data tiket ditemukan.</li>
          @endforelse
        </ul>
      </div>

      <!-- Harga -->
      <div class="mb-6 text-sm">
        <h3 class="text-lg font-semibold mb-2">Informasi Pembayaran</h3>
        <p>🎟️ Harga per Tiket: <strong>Rp{{ number_format($hargaTiket, 0, ',', '.') }}</strong></p>
        <p>👥 Jumlah Tiket: <strong>{{ count($details) }}</strong></p>
        <p class="text-blue-700 font-bold text-lg mt-2">💰 Total Bayar: Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
      </div>

      <!-- Placeholder QRIS -->
      <div class="text-center mt-8 text-gray-500 italic">
        <p><strong>QRIS dan detail pembayaran Tripay akan muncul di sini nanti.</strong></p>
      </div>

    </div>
  </div>

</body>
</html>
