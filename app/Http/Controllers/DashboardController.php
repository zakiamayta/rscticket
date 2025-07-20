<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $transactions = $this->getAllTransactionData($request);
        return view('admin.admin-dashboard', compact('transactions'));
    }

    public function getAllTransactionData(Request $request)
    {
        $sortBy = $request->input('sort_by');
        $allowedSorts = ['email', 'payment_status']; // hanya dari tabel utama

        $transactions = Transaction::with('details')
            ->when($request->payment_status, fn($q) =>
                $q->where('payment_status', $request->payment_status)
            )
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('email', 'like', '%' . $request->q . '%')
                          ->orWhereHas('details', fn($q2) =>
                              $q2->where('name', 'like', '%' . $request->q . '%')
                          );
                });
            })
            ->when($sortBy && in_array($sortBy, $allowedSorts), fn($q) =>
                $q->orderBy($sortBy)
            )
            ->get();

        // Sort by name manually (karena name ada di relasi)
        if ($sortBy === 'name') {
            $transactions = $transactions->sortBy(function ($transaction) {
                return optional($transaction->details->first())->name;
            })->values(); // reindex
        }

        return $transactions;
    }
}
