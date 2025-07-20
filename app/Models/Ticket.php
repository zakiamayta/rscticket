<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'transactions'; // Sesuaikan jika nama tabel berbeda

    protected $fillable = [
        'email',
        'checkout_time',
        'paid_time',
        'payment_status',
        'qris_invoice_id',
        'qris_url',
        'total_amount',
    ];

    protected $casts = [
        'checkout_time' => 'datetime',
        'paid_time' => 'datetime',
        'total_amount' => 'integer',
    ];
}
