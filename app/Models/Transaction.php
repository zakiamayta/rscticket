<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'email', 'checkout_time', 'paid_time', 'payment_status',
        'qris_invoice_id', 'qris_url', 'total_amount'
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}