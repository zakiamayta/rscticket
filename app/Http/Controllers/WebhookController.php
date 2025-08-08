<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class WebhookController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Cek isi request dari webhook
        $data = $request->all();
        Log::info('Xendit Webhook Received:', $data);

        // Proses jika status pembayaran adalah PAID
        if (isset($data['id']) && $data['status'] === 'PAID') {
            $transaction = Transaction::where('xendit_invoice_id', trim($data['id']))->first();

            if ($transaction) {
                // Update status pembayaran
                $transaction->payment_status = 'paid';
                $transaction->status = 'paid';
                $transaction->paid_time = now();

                try {
                    // Buat folder qrcodes jika belum ada
                    $qrPath = public_path('qrcodes');
                    if (!File::exists($qrPath)) {
                        File::makeDirectory($qrPath, 0755, true);
                    }

                    // Data yang akan dikodekan dalam QR
                    $qrData = route('absen.form', ['id' => $transaction->id]);


                    // Nama file QR
                    $qrFileName = 'ticket_' . $transaction->id . '.png';
                    $qrFullPath = $qrPath . '/' . $qrFileName;

                    // Generate QR
                    QrCode::format('png')
                        ->size(300)
                        ->generate($qrData, $qrFullPath);

                    // Simpan path ke database
                    $transaction->qr_code = 'qrcodes/' . $qrFileName;

                    Log::info('QR Code generated and saved', ['transaction_id' => $transaction->id]);
                } catch (\Exception $e) {
                    Log::error('Failed to generate QR Code', ['error' => $e->getMessage()]);
                }

                // Simpan semua perubahan
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
