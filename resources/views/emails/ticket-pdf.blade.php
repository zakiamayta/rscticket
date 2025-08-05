<!DOCTYPE html>
<html>
<head>
    <title>Tiket {{ $eventTitle }}</title>
    <style>
        body { font-family: sans-serif; }
        .ticket { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Konser: {{ $eventTitle }}</h2>
    <p>Tanggal: {{ $eventDate }}</p>
    <p>Jam: {{ $eventTime }}</p>
    <hr>

    @foreach ($details as $i => $detail)
        <div class="ticket">
            <p><strong>Nama:</strong> {{ $detail['name'] }}</p>
            <p><strong>ID Pembeli:</strong> {{ $buyerId }}</p>
            <p><strong>Tiket ke-{{ $i+1 }}</strong></p>
            <p><strong>Kode:</strong> {{ $detail['barcode'] }}</p>
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($detail['barcode'], 'C39') }}" alt="Barcode" />
        </div>
    @endforeach
</body>
</html>
