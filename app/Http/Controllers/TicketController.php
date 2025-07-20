<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TicketController extends Controller
{
    public function create()
    {
        return view('ticket.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nik' => 'required|array|min:1',
            'name' => 'required|array|min:1',
            'phone' => 'array',
        ]);

        $jumlahPembeli = count($request->nik);
        $hargaTiket = 50000;
        $totalAmount = $jumlahPembeli * $hargaTiket;

        $stok = DB::table('ticket_stock')->value('available_stock');
        if ($stok < $jumlahPembeli) {
            return back()->with('error', 'Stok tiket tidak mencukupi. Sisa stok: ' . $stok)->withInput();
        }

        foreach ($request->nik as $nik) {
            $exists = DB::table('transaction_details')->where('nik', $nik)->exists();
            if ($exists) {
                return back()->with('error', "NIK $nik sudah terdaftar sebelumnya.")->withInput();
            }
        }

        DB::beginTransaction();

        try {
            // Kurangi stok
            DB::table('ticket_stock')->update([
                'available_stock' => $stok - $jumlahPembeli
            ]);

            // Simpan transaksi utama dengan total_amount
            $transactionId = DB::table('transactions')->insertGetId([
                'email' => $request->email,
                'checkout_time' => Carbon::now(),
                'payment_status' => 'unpaid',
                'total_amount' => $totalAmount,
            ]);

            // Simpan detail pengunjung
            foreach ($request->nik as $i => $nik) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'nik' => $nik,
                    'name' => $request->name[$i],
                    'phone_number' => $request->phone[$i] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('ticket.payment', ['id' => $transactionId]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function payment($id)
    {
        $transaction = DB::table('transactions')->where('id', $id)->first();
        $details = DB::table('transaction_details')->where('transaction_id', $id)->get();

        $jumlahTiket = $details->count();
        $hargaTiket = 50000;
        $totalBayar = $jumlahTiket * $hargaTiket;

        return view('ticket.payment', compact('transaction', 'details', 'hargaTiket', 'totalBayar'));
    }
}
