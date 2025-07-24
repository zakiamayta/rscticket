<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleCallback(Request $request)
    {
        \Log::info('Xendit QR Callback:', $request->all());

        // TODO: Update status pembayaran di database, dsb
        return response()->json(['status' => 'callback received']);
    }
}
