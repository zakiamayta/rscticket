<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\TransactionExport;

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
    $allowedSorts = ['email', 'payment_status', 'checkout_time'];

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
        ->when($request->start_date && $request->end_date, function ($q) use ($request) {
            $q->whereBetween('checkout_time', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        })
        ->when($sortBy && in_array($sortBy, $allowedSorts), fn($q) =>
            $q->orderBy($sortBy, 'asc')
        )
        ->get();

    if ($sortBy === 'name') {
        $transactions = $transactions->sortBy(function ($transaction) {
            return optional($transaction->details->first())->name;
        })->values();
    }

    return $transactions;
}

public function exportPDF(Request $request)
{
    $transactions = $this->getAllTransactionData($request);
    $pdf = Pdf::loadView('admin.export-pdf', compact('transactions'));
    return $pdf->download('transactions.pdf');
}

public function exportSimpleExcel(Request $request): StreamedResponse
{
    $transactions = $this->getAllTransactionData($request);

    return response()->streamDownload(function () use ($transactions) {
        $writer = SimpleExcelWriter::streamDownload('transactions.xlsx');

        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $writer->addRow([
                    'Email'           => $transaction->email,
                    'Name'            => $detail->name,
                    'Phone Number'    => $detail->phone_number,
                    'Checkout Time'   => $transaction->checkout_time,
                    'Paid Time'       => $transaction->paid_time ?? '-',
                    'Payment Status'  => $transaction->payment_status,
                    'Total Amount'    => $transaction->total_amount,
                ]);
            }
        }

        $writer->close();
    }, 'transactions.xlsx');
}
    
}
