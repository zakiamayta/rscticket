<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket {{ $event->title }}</title>
</head>
<body style="font-family: sans-serif; text-align: center;">
    <h2>{{ $event->title }}</h2>
    <p>{{ \Carbon\Carbon::parse($event->date)->format('d M Y H:i') }} - {{ $event->location }}</p>

    <p><strong>Email Pemesan:</strong> {{ $transaction->email }}</p>
    <p><strong>Kode Transaksi:</strong> {{ $transaction->id }}</p>

    @if(isset($qrPath))
        <img src="{{ $qrPath }}" style="width:200px; margin-top: 20px;">
    @endif
</body>
</html>
