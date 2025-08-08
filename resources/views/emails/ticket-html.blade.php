<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>Halo {{ $transaction->customer_name }},</p>
    <p>Terima kasih sudah membeli tiket <strong>{{ $transaction->event_name }}</strong>.</p>
    <p>Tiket Anda terlampir dalam bentuk PDF pada email ini. Mohon simpan dan tunjukkan QR code saat masuk venue.</p>
    <p>Salam,<br>Tim Event</p>
</body>
</html>
