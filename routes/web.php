<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

// web.php
Route::get('/tiket', [TicketController::class, 'create'])->name('ticket.create');
Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');
Route::get('/tiket/{id}/pembayaran', [TicketController::class, 'payment'])->name('ticket.payment');
