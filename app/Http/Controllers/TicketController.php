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
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|array|min:1|max:3',
            'name.*' => 'required|string',
            'phone' => 'array',
            'phone.*' => 'nullable|string',
            'ticket_id' => 'required|integer',
            'qty' => 'required|integer|min:1|max:3'
        ]);

        DB::beginTransaction();

        try {
            // Ambil data tiket
            $ticket = DB::table('tickets')->where('id', $request->ticket_id)->lockForUpdate()->first();
            if (!$ticket || $ticket->stock < $request->qty) {
                return back()->with('error', 'Stok tiket tidak mencukupi.')->withInput();
            }

            $hargaTiket = $ticket->price;
            // ✅ Hapus admin fee
            $total = $request->qty * $hargaTiket;


            // Kurangi stok
            DB::table('tickets')->where('id', $ticket->id)
                ->update(['stock' => $ticket->stock - $request->qty]);

            $transactionId = DB::table('transactions')->insertGetId([
                'email' => $request->email,
                'checkout_time' => now(),
                'payment_status' => 'unpaid',
                'total_amount' => $total,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($request->name as $i => $name) {
                DB::table('ticket_attendees')->insert([
                    'transaction_id' => $transactionId,
                    'ticket_id' => $request->ticket_id,
                    'name' => $name,
                    'phone_number' => $request->phone[$i] ?? null, 
                ]);
            }


            // === Xendit Invoice ===
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
                'payment_methods' => ['QRIS'],
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
            'payment_methods' => ['QRIS'],
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


    public function show($id)
    {
        $transaction = Transaction::with('attendees')->find($id);

        if (!$transaction || $transaction->payment_status !== 'paid') {
            abort(404, 'Tiket tidak ditemukan atau belum dibayar.');
        }

        return view('ticket.viewqr', compact('transaction'));
    }


    
}
