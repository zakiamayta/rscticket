<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>Halo {{ $user->name }},</p>
    <p>Terima kasih sudah membeli tiket <strong>{{ $event->title }}</strong>.</p>
    <p>
        Tanggal: {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y H:i') }}<br>
        Lokasi: {{ $event->location }}
    </p>
    <p>Berikut adalah QR Code tiket Anda:</p>
    @if(!empty($transaction->qr_code))
        <img src="{{ $message->embed(public_path('storage/qr_codes/' . $transaction->qr_code)) }}" alt="QR Code" style="width:200px;">
    @endif
    <p>Salam,<br>Tim Event</p>
</body>
</html>
