<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;

class WebhookController extends Controller
{
    public function __construct()
    {
        // ✅ Disable CSRF untuk route ini saja
        $this->middleware(function ($request, $next) {
            app('Illuminate\Contracts\Http\Kernel')
                ->withoutMiddleware([VerifyCsrfToken::class]);
            return $next($request);
        });
    }

    public function handleCallback(Request $request)
    {
        \Log::info('Xendit QR Callback:', $request->all());

        return response()->json(['status' => 'callback received']);
    }
}
