<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Code - {{ $guest->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; }
        .qrcode { margin: 20px auto; padding: 10px; border: 1px solid #ddd; display: inline-block; }
    </style>
</head>
<body>
    <h2>QR Code untuk {{ $guest->name }}</h2>
    <p>{{ $guest->email }}</p>
    <div class="qrcode">
        <img src="{{ public_path('qrcodes/ticket_' . $guest->id . '.png') }}" width="250" height="250" alt="QR Code">
    </div>
    <p>URL: {{ route('absen.form', ['id' => $guest->id]) }}</p>
</body>
</html>
