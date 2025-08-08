<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'user_id',
    'total_price',
    'payment_method',
    'status',
    'email',
    'checkout_time',
    'paid_time',
    'payment_status',
    'xendit_invoice_id',
    'xendit_invoice_url',
    'qr_code',
    'total_amount'
];

    // Relasi ke tabel ticket_attendees
    public function attendees()
    {
        return $this->hasMany(TicketAttendee::class, 'transaction_id');
    }

    // Aktifkan timestamp jika kamu pakai created_at & updated_at di tabel
    public $timestamps = true;
}
