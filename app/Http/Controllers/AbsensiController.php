<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function showPasswordForm($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            abort(404);
        }

        return view('absen.form', compact('id'));
    }

    public function handleScan(Request $request, $id)
    {
        // Validasi input password
        $request->validate([
            'password' => 'required',
        ]);

        // Cek password
        if ($request->password !== config('app.gate_password', 'gate123')) {
            return back()->with('error', 'Password salah.');
        }

        // Ambil transaksi berdasarkan ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Cek jika sudah registrasi sebelumnya
        if ($transaction->is_registered) {
            return view('absen.warning', [
                'message' => 'Anda sudah melakukan registrasi.'
            ]);
        }

        // Tandai sebagai sudah registrasi
        $transaction->is_registered = true;
        $transaction->registered_at = now();
        $transaction->save();

        // Ambil daftar peserta dari ticket_attendees
        $details = DB::table('ticket_attendees')
            ->where('transaction_id', $transaction->id)
            ->get();

        // Kirim ke halaman success
        return view('absen.success', compact('transaction', 'details'));
    }
}
