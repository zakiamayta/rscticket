{{-- resources/views/emails/ticket.blade.php --}}
<x-mail::message>
# Halo {{ $buyerName ?? $transaction->email }}

Terima kasih telah melakukan pembayaran. Berikut adalah detail tiket Anda:

**Event:** {{ $event->title }}  
**Tanggal:** {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y H:i') }}  
**Lokasi:** {{ $event->location }}

QR Code tiket Anda ada pada lampiran email ini (format PDF dan PNG).  
Silakan tunjukkan QR Code tersebut saat memasuki venue.

Salam hangat,  
**RSCTix**

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
