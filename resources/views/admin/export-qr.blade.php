<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Code untuk {{ $guest->name }}</title>
    <style>
        body { font-family: sans-serif; text-align: center; }
        .qr-box {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            display: inline-block;
        }
        img {
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
    <h2>QR Code untuk {{ strtoupper($guest->name) }}</h2>
    <p>Email: {{ $guest->email }}</p>

    <div class="qr-box">
        <img src="{{ public_path('qrcodes/ticket_' . $guest->id . '.png') }}" alt="QR Code">
    </div>

    <p style="margin-top:20px; font-size:12px;">
        Scan QR atau buka link:<br>
        {{ route('absen.form', ['id' => $guest->id]) }}
    </p>
</body>
</html>
