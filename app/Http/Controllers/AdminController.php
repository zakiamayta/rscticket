<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function absensi()
    {
        $transactions = Transaction::with(['attendance', 'attendees'])->orderBy('checkout_time', 'desc')->get();
        return view('absen.index', compact('transactions')); // ← path diganti ke 'absen.index'
    }


    public function markPresence(Transaction $transaction)
    {
        $attendance = Attendance::updateOrCreate(
            ['transaction_id' => $transaction->id],
            ['status' => 'present', 'registered_at' => now()]
        );

        return back()->with('success', 'Absensi berhasil dicatat.');
    }

    public function cancelPresence(Transaction $transaction)
    {
        Attendance::where('transaction_id', $transaction->id)->delete();
        return back()->with('success', 'Absensi berhasil dibatalkan.');
    }

}
