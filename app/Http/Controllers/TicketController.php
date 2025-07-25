<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Xendit\Xendit;
use Xendit\QRCode;
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
            'name' => 'required|array|min:1',
            'phone' => 'array',
        ]);

        $jumlahPembeli = count($request->name);
        $hargaTiket = 50000;
        $totalAmount = $jumlahPembeli * $hargaTiket;

        $stok = DB::table('ticket_stock')->value('available_stock');
        if ($stok < $jumlahPembeli) {
            return back()->with('error', 'Stok tiket tidak mencukupi. Sisa stok: ' . $stok)->withInput();
        }


        DB::beginTransaction();

        try {
            // Kurangi stok
            DB::table('ticket_stock')->update([
                'available_stock' => $stok - $jumlahPembeli
            ]);

            // Simpan transaksi utama
            $transactionId = DB::table('transactions')->insertGetId([
                'email' => $request->email,
                'checkout_time' => Carbon::now(),
                'payment_status' => 'unpaid',
                'total_amount' => $totalAmount,
            ]);

            // Simpan detail pengunjung
            foreach ($request->name as $i => $name) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'name' => $name,
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
        $transaction = Transaction::findOrFail($id);
        $details = $transaction->details;
        $hargaTiket = 50000;
        $totalBayar = count($details) * $hargaTiket;

        $errorMessage = null;
        $qrURL = null;
        $expiryTime = null;

        try {
            $apiKey = env('XENDIT_API_KEY');
            Xendit::setApiKey($apiKey);

            // Buat external_id unik untuk menghindari duplikat
            $externalId = 'trx-' . $transaction->id . '-' . time();

            $qr = QRCode::create([
                'external_id' => $externalId,
                'type' => 'DYNAMIC',
                'amount' => $totalBayar,
                'currency' => 'IDR',
                'callback_url' => 'https://webhook.site/your-callback',
                'metadata' => [
                    'order_id' => $transaction->id,
                    'user_email' => $transaction->email,
                ],
            ]);

            $qrURL = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qr['qr_string']);
            // Simpan ID & URL QR ke database
            $transaction->qris_invoice_id = $qr['id'];
            $transaction->qris_url = $qr['qr_string'];
            $transaction->save();
          
            // Estimasi expired 15 menit dari sekarang
            $expiryTime = now()->addMinutes(15)->toIso8601String();

        } catch (\Exception $e) {
            \Log::error('Xendit QRIS Error: ' . $e->getMessage());
            $errorMessage = $e->getMessage();
        }

        return view('ticket.payment', compact(
            'transaction',
            'details',
            'hargaTiket',
            'totalBayar',
            'qrURL',
            'expiryTime',
            'errorMessage'
        ));
    }




}
