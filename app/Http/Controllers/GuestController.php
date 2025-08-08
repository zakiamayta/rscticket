<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // gunakan model yang benar
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function showQR($id)
    {
        $guest = Transaction::findOrFail($id); // pakai transaction
        return view('admin.guest-qr', compact('guest'));
    }
}
