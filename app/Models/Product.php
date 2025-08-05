<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Nama tabel (opsional jika sesuai konvensi Laravel)
    protected $table = 'products';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'stock',
        'event_id',
        'image'
    ];

    // Relasi opsional (jika kamu punya tabel events)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
