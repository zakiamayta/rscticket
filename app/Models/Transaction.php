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
        'qris_invoice_id',
        'qris_url',
        'total_amount'
    ];

    // Relasi ke tabel ticket_attendees
    public function attendees()
    {
        return $this->hasMany(TicketAttendee::class, 'transaction_id');
    }

    public $timestamps = false;
}
