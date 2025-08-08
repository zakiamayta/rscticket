<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket {{ $transaction->event_name }}</title>
</head>
<body style="font-family: sans-serif; text-align: center;">
    <h2>{{ $transaction->event_name }}</h2>
    <p>{{ $transaction->event_date }} - {{ $transaction->event_time }}</p>
    <p><strong>Nama:</strong> {{ $transaction->customer_name }}</p>
    <p><strong>Kode Tiket:</strong> {{ $transaction->id }}</p>
    <img src="{{ $qrPath }}" style="width:200px; margin-top: 20px;">
</body>
</html>
