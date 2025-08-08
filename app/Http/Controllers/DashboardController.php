<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\TicketAttendee;

class DashboardController extends Controller
{
    public function absensi()
    {
        $attendees = TicketAttendee::with('transaction')->get();

        return view('admin.admin-absensi', compact('attendees'));
    }
    
    public function index(Request $request)
    {
        $transactions = $this->getAllTransactionData($request);

        // Tambahkan total uang paid
        $totalPaidAmount = Transaction::where('payment_status', 'paid')->sum('total_amount');

        return view('admin.admin-dashboard', compact('transactions', 'totalPaidAmount'));
    }

    public function getAllTransactionData(Request $request)
    {
        $sortBy = $request->input('sort_by');
        $allowedSorts = ['email', 'payment_status', 'checkout_time'];

        $transactions = Transaction::with('attendees')
            ->when($request->payment_status, fn($q) =>
                $q->where('payment_status', $request->payment_status)
            )
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('email', 'like', '%' . $request->q . '%')
                          ->orWhereHas('attendees', fn($q2) =>
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
                return optional($transaction->attendees->first())->name;
            })->values();
        }

        return $transactions;
    }

    public function exportPDF(Request $request)
    {
        $transactions = $this->getAllTransactionData($request);
        $totalPaidAmount = $transactions->where('payment_status', 'paid')->sum('total_amount');

        $pdf = Pdf::loadView('admin.export-pdf', compact('transactions', 'totalPaidAmount'));
        return $pdf->download('transactions.pdf');
    }

    public function exportSimpleExcel(Request $request): StreamedResponse
    {
        $transactions = $this->getAllTransactionData($request);
        $totalPaidAmount = $transactions->where('payment_status', 'paid')->sum('total_amount');

        return response()->streamDownload(function () use ($transactions, $totalPaidAmount) {
            $writer = SimpleExcelWriter::streamDownload('transactions.xlsx');

            // Header
            $writer->addRow([
                'Email', 'Name', 'Phone Number', 'Checkout Time', 'Paid Time', 'Payment Status', 'Total Amount'
            ]);

            foreach ($transactions as $transaction) {
                if ($transaction->attendees->isEmpty()) {
                    $writer->addRow([
                        $transaction->email,
                        '-',
                        '-',
                        $transaction->checkout_time,
                        $transaction->paid_time ?? '-',
                        $transaction->payment_status,
                        $transaction->total_amount,
                    ]);
                } else {
                    foreach ($transaction->attendees as $attendee) {
                        $writer->addRow([
                            $transaction->email,
                            $attendee->name,
                            $attendee->phone_number,
                            $transaction->checkout_time,
                            $transaction->paid_time ?? '-',
                            $transaction->payment_status,
                            $transaction->total_amount,
                        ]);
                    }
                }
            }

            // Tambahkan total di akhir
            $writer->addRow([
                '', '', '', '', '', 'Total Paid',
                $totalPaidAmount,
            ]);

            $writer->close();
        }, 'transactions.xlsx');
        
    } 


};
