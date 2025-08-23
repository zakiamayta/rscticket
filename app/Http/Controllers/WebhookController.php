<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketEmail;

class WebhookController extends Controller
{
    // Fungsi reusable untuk generate QR Code
    public function generateTicketQRCode($transaction)
    {
        try {
            $qrPath = public_path('qrcodes');
            if (!File::exists($qrPath)) {
                File::makeDirectory($qrPath, 0755, true);
            }

            $qrData = route('tickets.show', ['id' => $transaction->id]);
            $qrFileName = 'ticket_' . $transaction->id . '.png';
            $qrFullPath = $qrPath . '/' . $qrFileName;

            QrCode::format('png')
                ->size(300)
                ->generate($qrData, $qrFullPath);

            $transaction->qr_code = 'qrcodes/' . $qrFileName;
            $transaction->save();

            Log::info('QR Code generated and saved', [
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate QR Code', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
        }
    }

    // Fungsi reusable untuk kirim email tiket
    public function sendTicketEmail($transaction)
    {
        try {
            Mail::to($transaction->email)->send(new TicketEmail($transaction));
            Log::info('Ticket email sent successfully', [
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send ticket email', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
        }
    }

    // Handle callback dari Xendit untuk event berbayar
    public function handleCallback(Request $request)
    {
        $data = $request->all();
        Log::info('Xendit Webhook Received:', $data);

        if (isset($data['id']) && strtoupper($data['status']) === 'PAID') {
            $transaction = Transaction::where('xendit_invoice_id', trim($data['id']))->first();

            if ($transaction) {
                $transaction->payment_status = 'paid';
                $transaction->status = 'paid';
                $transaction->paid_time = now();
                $transaction->save();

                // Generate QR dan kirim email
                $this->generateTicketQRCode($transaction);
                $this->sendTicketEmail($transaction);

                return response()->json(['message' => 'Transaction updated'], 200);
            }

            Log::warning('Transaction not found for invoice ID: ' . $data['id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json(['message' => 'Invalid webhook data'], 400);
    }

    // Proses order event gratis
    public function processFreeEventOrder(Request $request)
    {
        // Simpan transaksi (contoh minimal)
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->event_id = $request->event_id;
        $transaction->email = $request->email;
        $transaction->payment_status = 'paid';
        $transaction->status = 'paid';
        $transaction->paid_time = now();
        $transaction->save();

        // Generate QR dan kirim email
        $this->generateTicketQRCode($transaction);
        $this->sendTicketEmail($transaction);

        return response()->json(['message' => 'Free event ticket created', 'transaction' => $transaction]);
    }
}
