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
    public function handleCallback(Request $request)
    {
        // Cek isi request dari webhook
        $data = $request->all();
        Log::info('Xendit Webhook Received:', $data);

        // Proses jika status pembayaran adalah PAID
        if (isset($data['id']) && strtoupper($data['status']) === 'PAID') {
            $transaction = Transaction::with('items') // kalau ada relasi item tiket
                ->where('xendit_invoice_id', trim($data['id']))
                ->first();

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
                    $qrData = route('tickets.show', ['id' => $transaction->id]);

                    // Nama file QR
                    $qrFileName = 'ticket_' . $transaction->id . '.png';
                    $qrFullPath = $qrPath . '/' . $qrFileName;

                    // Generate QR
                    QrCode::format('png')
                        ->size(300)
                        ->generate($qrData, $qrFullPath);

                    // Simpan path ke database
                    $transaction->qr_code = 'qrcodes/' . $qrFileName;

                    Log::info('QR Code generated and saved', [
                        'transaction_id' => $transaction->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to generate QR Code', [
                        'error' => $e->getMessage(),
                        'transaction_id' => $transaction->id
                    ]);
                }

                // Simpan perubahan transaksi
                $transaction->save();

                try {
                    // Kirim email tiket
                    $this->sendTicketEmail($transaction);
                    Log::info('Ticket email sent successfully', [
                        'transaction_id' => $transaction->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send ticket email', [
                        'error' => $e->getMessage(),
                        'transaction_id' => $transaction->id
                    ]);
                }

                return response()->json(['message' => 'Transaction updated'], 200);
            }

            Log::warning('Transaction not found for invoice ID: ' . $data['id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json(['message' => 'Invalid webhook data'], 400);
    }

        private function sendTicketEmail(Transaction $transaction)
        {
            try {
                // Path QR code
                $qrPath = public_path($transaction->qr_code);

                // Generate PDF tiket
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.ticket-pdf', [
                    'transaction' => $transaction,
                    'qrPath' => $qrPath
                ]);

                $pdfPath = storage_path('app/public/tickets/ticket_' . $transaction->id . '.pdf');
                $pdf->save($pdfPath);

                // Kirim email + lampiran PDF
                \Mail::to($transaction->customer_email)
                    ->send(new \App\Mail\TicketEmail($transaction, $pdfPath));

                \Log::info('Ticket email sent', ['transaction_id' => $transaction->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to send ticket email', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    // WebhookController.php
    public function show(Request $request)
    {
        // 1. Ambil payload dari Xendit
        $data = $request->all();

        // 2. Cek apakah statusnya "PAID"
        if (isset($data['status']) && $data['status'] === 'PAID') {

            // 3. Cari transaksi berdasarkan invoice_id / external_id
            $transaction = Transaction::where('invoice_id', $data['id'])->first();

            if ($transaction) {
                // 4. Update status transaksi
                $transaction->status = 'PAID';
                $transaction->save();

                // 5. Kirim email tiket ke setiap pembeli
                foreach ($transaction->items as $item) {
                    Mail::to($item->email)->send(new TicketEmail($transaction, $item));
                }
            }
        }

        // 6. Balas ke Xendit bahwa webhook diterima
        return response()->json(['status' => 'success']);
    }

};