<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Xendit\Xendit;
use Xendit\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\Facades\DNS1D;

class TicketController extends Controller
{
    public function form(Request $request)
    {
        $eventId = $request->query('event_id');

        if (!$eventId) {
            abort(404, 'Event tidak ditemukan.');
        }

        $event = DB::table('events')->where('id', $eventId)->first();

        if (!$event) {
            abort(404, 'Event tidak ditemukan.');
        }

        $tickets = DB::table('tickets')->where('event_id', $eventId)->get();

        return view('ticket.form', compact('event', 'tickets'));
    }

    public function store(Request $request)
    {
        // Ambil tiket dan event
        $ticket = DB::table('tickets')->where('id', $request->ticket_id)->first();
        if (!$ticket) {
            return back()->with('error', 'Tiket tidak ditemukan.')->withInput();
        }

        $event = DB::table('events')->where('id', $ticket->event_id)->first();
        if (!$event) {
            return back()->with('error', 'Event tidak ditemukan.')->withInput();
        }

        // Validasi dinamis sesuai max_tickets_per_email
        $request->validate([
            'email' => [
                'required', 'email',
                function ($attribute, $value, $fail) use ($event) {
                    if ($event->max_tickets_per_email == 1) {
                        $exists = DB::table('transactions')
                            ->join('ticket_attendees', 'transactions.id', '=', 'ticket_attendees.transaction_id')
                            ->join('tickets', 'ticket_attendees.ticket_id', '=', 'tickets.id')
                            ->where('tickets.event_id', $event->id)
                            ->where('transactions.email', $value)
                            ->exists();

                        if ($exists) {
                            $fail('Email ini sudah digunakan untuk event ini.');
                        }
                    }
                }
            ],
            'name' => 'required|array|min:1|max:' . $event->max_tickets_per_email,
            'name.*' => 'required|string',
            'phone' => 'array',
            'phone.*' => 'nullable|string',
            'ticket_id' => 'required|integer',
            'qty' => 'required|integer|min:1|max:' . $event->max_tickets_per_email,
        ]);

        DB::beginTransaction();
        try {
            // Lock stok tiket
            $ticket = DB::table('tickets')->where('id', $request->ticket_id)->lockForUpdate()->first();
            if ($ticket->stock < $request->qty) {
                return back()->with('error', 'Stok tiket tidak mencukupi.')->withInput();
            }

            $hargaTiket = $ticket->price;
            $total = $request->qty * $hargaTiket;

            // Kurangi stok
            DB::table('tickets')->where('id', $ticket->id)
                ->update(['stock' => $ticket->stock - $request->qty]);

            // Simpan transaksi
            $transactionId = DB::table('transactions')->insertGetId([
                'event_id' => $ticket->event_id,
                'email' => $request->email,
                'checkout_time' => now(),
                'payment_status' => $hargaTiket == 0 ? 'paid' : 'unpaid',
                'total_amount' => $total,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Simpan data peserta
            foreach ($request->name as $i => $name) {
                DB::table('ticket_attendees')->insert([
                    'transaction_id' => $transactionId,
                    'ticket_id' => $request->ticket_id,
                    'name' => $name,
                    'phone_number' => $request->phone[$i] ?? null,
                ]);
            }

            if ($hargaTiket == 0) {
                // Ambil data transaksi dari model
                $transaction = \App\Models\Transaction::find($transactionId);

                $transaction->payment_status = 'paid';
                $transaction->status = 'paid';
                $transaction->paid_time = now();
                $transaction->save();

                app(\App\Http\Controllers\WebhookController::class)->generateTicketQRCode($transaction);
                app(\App\Http\Controllers\WebhookController::class)->sendTicketEmail($transaction);

                DB::commit();
                return redirect()->route('ticket.success', ['id' => $transactionId])
                    ->with('success', 'Pendaftaran berhasil. Tiket telah dikirim.');
            }


            // Tiket berbayar → proses Xendit
            Xendit::setApiKey(env('XENDIT_API_KEY'));
            $externalId = 'trx-' . $transactionId . '-' . time();
            $params = [
                'external_id' => $externalId,
                'payer_email' => $request->email,
                'description' => 'Pembelian Tiket ' . $ticket->name,
                'amount' => $total,
                'success_redirect_url' => route('ticket.success', ['id' => $transactionId]),
                'failure_redirect_url' => route('ticket.failed', ['id' => $transactionId]),
                'currency' => 'IDR',
                'invoice_duration' => 15 * 60,
                'payment_methods' => ['BNI', 'MANDIRI', 'BSI', 'BCA', 'QRIS'],
            ];

            $invoice = Invoice::create($params);
            DB::table('transactions')->where('id', $transactionId)->update([
                'xendit_invoice_url' => $invoice['invoice_url'],
                'xendit_invoice_id' => $invoice['id'],
            ]);

            DB::commit();
            return redirect()->route('ticket.payment', ['id' => $transactionId]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat checkout: '.$e->getMessage());
            return back()->with('error', 'DB Error: '.$e->getMessage());
        }
    }

    public function payment($id)
    {
        $transaction = DB::table('transactions')->find($id);
        $details = DB::table('ticket_attendees')->where('transaction_id', $id)->get();

        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $ticket = null;
        if ($details->isNotEmpty()) {
            $ticketId = $details->first()->ticket_id;
            $ticket = DB::table('tickets')->where('id', $ticketId)->first();
        }

        $hargaTiket = $ticket ? $ticket->price : 0;
        $totalBayar = $transaction->total_amount;

        return view('ticket.payment', compact('transaction', 'details', 'hargaTiket', 'totalBayar'));
    }


    public function processPayment($id)
    {
        $transaction = DB::table('transactions')->find($id);
        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $email = $transaction->email;

        Xendit::setApiKey(env('XENDIT_API_KEY'));

        $externalId = 'trx-' . $transaction->id . '-' . time();
        $params = [
            'external_id' => $externalId,
            'payer_email' => $email,
            'description' => 'Pembelian Tiket Event',
            'amount' => $transaction->total_amount,
            'success_redirect_url' => route('ticket.success', ['id' => $transaction->id]),
            'failure_redirect_url' => route('ticket.failed', ['id' => $transaction->id]),
            'currency' => 'IDR',
            'invoice_duration' => 15 * 60,
            'payment_methods' => ['BNI', 'MANDIRI', 'BSI', 'BCA', 'QRIS'],
        
        ];

        $invoice = Invoice::create($params);

        DB::table('transactions')->where('id', $transaction->id)->update([
            'xendit_invoice_url' => $invoice['invoice_url'],
            'xendit_invoice_id' => $invoice['id'],
        ]);

        return redirect($invoice['invoice_url']);
    }
    public function cancel($id)
    {
        DB::beginTransaction();

        try {
            $transaction = DB::table('transactions')->where('id', $id)->first();

            if (!$transaction) {
                return back()->with('error', 'Transaksi tidak ditemukan.');
            }
            if ($transaction->payment_status == 'paid') {
                return back()->with('error', 'Transaksi ini sudah dibayar dan tidak dapat dibatalkan.');
            }
            $attendees = DB::table('ticket_attendees')->where('transaction_id', $id)->get();

            foreach ($attendees as $attendee) {
                DB::table('tickets')->where('id', $attendee->ticket_id)->increment('stock', 1);
            }

            DB::table('ticket_attendees')->where('transaction_id', $id)->delete();

            DB::table('transactions')->where('id', $id)->delete();

            DB::commit();

            return redirect('/')->with('success', 'Transaksi berhasil dibatalkan dan stok tiket telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat membatalkan transaksi: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan transaksi.');
        }
    }

    public function success($id)
    {
        $transaction = DB::table('transactions')->find($id);

        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $details = DB::table('ticket_attendees')
            ->where('transaction_id', $id)
            ->get();

        if ($transaction->payment_status !== 'paid') {
            $hargaTiket = $details->isNotEmpty() ? 50000 : 0; 
            $totalBayar = $transaction->total_amount;

            return view('ticket.payment', [
                'transaction' => $transaction,
                'details' => $details,
                'hargaTiket' => $hargaTiket,
                'totalBayar' => $totalBayar,
                'errorMessage' => 'Pembayaran belum terverifikasi. Silakan selesaikan pembayaran Anda.'
            ]);
        }

        // Jika pembayaran berhasil
        return view('ticket.success', [
            'transaction' => $transaction,
            'details' => $details
        ]);
    }


    public function failed($id)
    {
        $transaction = DB::table('transactions')->find($id);
        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $details = DB::table('ticket_attendees')->where('transaction_id', $id)->get();

        return view('ticket.failed', [
            'transaction' => $transaction,
            'details' => $details
        ]);
    }
    
}
