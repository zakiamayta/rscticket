<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WebhookController;

Route::post('/webhook/qris/callback', [WebhookController::class, 'handleCallback'])->name('webhook.qris.callback');

Route::get('/', function () {
    return view('home');
});
Route::get('/cara-memesan', function () {
    return view('cara-memesan');
});

// Form tiket
Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');
Route::get('/tiket', [TicketController::class, 'create'])->name('ticket.create');

// Halaman pembayaran
Route::get('/tiket/{id}/pembayaran', [TicketController::class, 'payment'])->name('ticket.payment');


Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


});


