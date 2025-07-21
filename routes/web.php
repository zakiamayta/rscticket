<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('home');
});

// Form tiket
Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');
Route::get('/tiket', [TicketController::class, 'create'])->name('ticket.create');

// Halaman pembayaran
Route::get('/tiket/{id}/pembayaran', [TicketController::class, 'payment'])->name('ticket.payment');

// Dashboard admin
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
