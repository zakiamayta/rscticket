<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Xendit Webhook received', $request->all());

        $event = $request->all();

        if (
            isset($event['status']) &&
            $event['status'] === 'PAID' &&
            isset($event['external_id'])
        ) {
            $externalId = $event['external_id'];
            $trxId = explode('-', $externalId)[1] ?? null;

            if ($trxId) {
                $transaction = Transaction::with('details')->find($trxId);
                if ($transaction && $transaction->payment_status !== 'paid') {
                    $transaction->payment_status = 'paid';
                    $transaction->paid_at = now();
                    $transaction->save();

                    // Kirim email tiket
                    $ticketCtrl = new TicketController();
                    $ticketCtrl->sendTicketEmail($transaction);

                    return response()->json(['message' => 'Payment processed'], 200);
                }
            }
        }

        return response()->json(['message' => 'Ignored or invalid data'], 200);
    }
}
