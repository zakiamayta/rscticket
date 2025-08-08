{{-- resources/views/emails/ticket.blade.php --}}
<x-mail::message>
# Halo {{ $transaction->customer_name }}

Terima kasih telah melakukan pembayaran. Berikut adalah detail tiket konser Anda:

**Event:** {{ $transaction->event_name }}  
**Tanggal:** {{ $transaction->event_date }}  
**Lokasi:** {{ $transaction->event_location }}

Silakan scan QR code pada lampiran saat masuk ke venue.

<x-mail::button :url="route('tickets.show', $transaction->id)">
Lihat Tiket Online
</x-mail::button>

Salam,  
**RSCTix**

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
