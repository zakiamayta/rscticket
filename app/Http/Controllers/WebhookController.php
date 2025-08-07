<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Cek isi request
        $data = $request->all();
        Log::info('Xendit Webhook Received:', $data);

        // Logika update jika status dari webhook adalah PAID
        if (isset($data['id']) && $data['status'] === 'PAID') {
            $transaction = Transaction::where('xendit_invoice_id', trim($data['id']))->first();

            if ($transaction) {
                $transaction->payment_status = 'paid';  // Update kolom enum
                $transaction->status = 'paid';          // Jika ingin sekalian ubah status umum
                $transaction->paid_time = now();        // Catat waktu pembayaran
                $transaction->save();

                Log::info('Transaction updated successfully', ['transaction_id' => $transaction->id]);

                return response()->json(['message' => 'Transaction updated'], 200);
            }

            Log::warning('Transaction not found for invoice ID: ' . $data['id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json(['message' => 'Ignored'], 200);
    }
}
