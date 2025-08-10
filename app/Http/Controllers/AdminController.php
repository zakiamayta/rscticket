<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class AdminController extends Controller
{
    use App\Models\TicketAttendee;

public function absensi()
{
    $attendees = TicketAttendee::with('transaction')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('absen.index', compact('attendees'));
}




    public function absenManual($id)
    {
        $attendee = TicketAttendee::find($id);

        if (! $attendee) {
            return redirect()->back()->with('error', 'Peserta tidak ditemukan.');
        }

        // Tandai peserta sudah absen
        $attendee->is_registered = true;
        $attendee->registered_at = now();
        $attendee->save();

        return redirect()->back()->with('success', 'Absensi manual berhasil dilakukan.');
    }

    public function batalAbsen($id)
    {
        $attendee = TicketAttendee::find($id);

        if (! $attendee) {
            return redirect()->back()->with('error', 'Peserta tidak ditemukan.');
        }

        // Batalkan status absen peserta
        $attendee->is_registered = false;
        $attendee->registered_at = null;
        $attendee->save();

        return redirect()->back()->with('success', 'Status absensi berhasil dibatalkan.');
    }


}
