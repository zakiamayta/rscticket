<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // gunakan model yang benar
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // tambahkan ini

class GuestController extends Controller
{
    // Tampilkan QR di browser
    public function showQR($id)
    {
        $guest = Transaction::findOrFail($id); // pakai transaction
        return view('admin.guest-qr', compact('guest'));
    }
    public function exportGuestQR($id)
    {
        $guest = Transaction::with(['event', 'attendees'])->findOrFail($id);
        $event = $guest->event;

        $pdf = Pdf::loadView('admin.export-qr', compact('guest', 'event'));
        return $pdf->download('guest_qr_' . $guest->id . '.pdf');
    }



}
