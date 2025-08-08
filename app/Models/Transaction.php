<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'email',
        'checkout_time',
        'paid_time',
        'payment_status',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'total_amount',
        'qr_code'
    ];

    // Relasi ke tabel ticket_attendees
    public function attendees()
    {
        return $this->hasMany(TicketAttendee::class, 'transaction_id');
    }

    // Aktifkan timestamp jika kamu pakai created_at & updated_at di tabel
    public $timestamps = true;
}
