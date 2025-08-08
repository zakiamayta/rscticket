@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>e-Ticket</h1>
    <p>Transaksi ID: {{ $transaction->id }}</p>
    <p>Email: {{ $transaction->email }}</p>

    <div>
        <img src="{{ asset($transaction->qr_code) }}" alt="QR Code Tiket">
    </div>

    <h3>Daftar Peserta:</h3>
    <ul>
        @foreach($transaction->attendees as $attendee)
            <li>{{ $attendee->name }} - {{ $attendee->phone_number }}</li>
        @endforeach
    </ul>
</div>
@endsection
