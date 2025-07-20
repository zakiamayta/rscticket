<!DOCTYPE html>
<html>
<head>
    <title>Halaman Pembayaran</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .status { margin-bottom: 20px; }
        .ticket-list { margin-top: 20px; }
        ul { list-style: none; padding: 0; }
        li { background: #f0f0f0; margin-bottom: 10px; padding: 10px; border-radius: 5px; }
        .qris-placeholder { margin-top: 40px; font-style: italic; color: gray; }
    </style>
</head>
<body>

    <h2>Detail Pembayaran</h2>

    <div class="status">
        <p><strong>Email:</strong> {{ $transaction->email ?? 'Tidak tersedia' }}</p>
        <p><strong>Status Pembayaran:</strong> {{ $transaction->payment_status ?? 'Belum diketahui' }}</p>
        <p><strong>Waktu Checkout:</strong> {{ $transaction->checkout_time ?? '-' }}</p>
    </div>

    <div class="ticket-list">
        <h3>Daftar Tiket</h3>
        <ul>
            @forelse($details as $d)
                <li>
                    <strong>{{ $d->name ?? 'Tanpa Nama' }}</strong> <br>
                    NIK: {{ $d->nik ?? '-' }} <br>
                    No. Telepon: {{ $d->phone_number ?? 'Tidak ada nomor' }}
                </li>
            @empty
                <li>Tidak ada data tiket ditemukan.</li>
            @endforelse
        </ul>
        <h3>Harga Tiket</h3>
        <p>Harga per Tiket: Rp{{ number_format($hargaTiket, 0, ',', '.') }}</p>
        <p>Jumlah Tiket: {{ count($details) }}</p>
        <p><strong>Total yang Harus Dibayar: Rp{{ number_format($totalBayar, 0, ',', '.') }}</strong></p>

    </div>

    <hr>
    <p class="qris-placeholder"><strong>QRIS dan detail pembayaran Tripay akan muncul di sini nanti.</strong></p>

</body>
</html>
