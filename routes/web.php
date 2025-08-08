<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\GuestController;
use App\Http\Middleware\VerifyCsrfToken;


/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ====================
// FRONTEND ROUTES
// ====================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Cara Memesan
Route::get('/cara-memesan', function () {
    return view('cara-memesan');
})->name('cara.memesan');

// Band Info
Route::get('/band/negatifa', function () {
    return view('band.negatifa');
})->name('band.negatifa');

// ====================
// TICKET ROUTES
// ====================

// Form tiket
Route::get('/tiket', [TicketController::class, 'form'])->name('ticket.form');
Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');

Route::get('/band/negatifa', function () {
    return view('band.negatifa');
})->name('band.negatifa');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about.us');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Halaman pembayaran
Route::get('/ticket/payment/{id}', [TicketController::class, 'payment'])->name('ticket.payment');
Route::post('/ticket/pay/{id}', [TicketController::class, 'processPayment'])->name('ticket.pay');

// Batalkan transaksi
Route::post('/ticket/cancel/{id}', [TicketController::class, 'cancel'])->name('ticket.cancel');

// Status transaksi
Route::get('/tiket/success/{id}', [TicketController::class, 'success'])->name('ticket.success');
Route::get('/tiket/failed/{id}', [TicketController::class, 'failed'])->name('ticket.failed');
Route::get('/tickets/{id}', [WebhookController::class, 'show'])->name('tickets.show');

// Webhook callback (Xendit & QRIS)

// ====================
// ADMIN ROUTES
// ====================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/export-excel', [DashboardController::class, 'exportSimpleExcel'])->name('admin.dashboard.export.excel');
    Route::get('/admin/dashboard/export-pdf', [DashboardController::class, 'exportPDF'])->name('admin.dashboard.export.pdf');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
});


// ====================
// QR ROUTES
// ====================
Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
Route::get('/guest/qr/{id}', [GuestController::class, 'showQr'])->name('guests.qr');

