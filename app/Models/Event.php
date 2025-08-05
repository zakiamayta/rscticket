<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['id', 'title', 'description', 'date', 'location', 'poster', 'created_at', 'updated_at'
]; // sesuaikan kolom

public function products()
{
    return $this->hasMany(Product::class);
}

}
