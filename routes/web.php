<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// web.php
Route::get('/tiket', [TicketController::class, 'create'])->name('ticket.create');
Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');
Route::get('/tiket/{id}/pembayaran', [TicketController::class, 'payment'])->name('ticket.payment');


Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


});