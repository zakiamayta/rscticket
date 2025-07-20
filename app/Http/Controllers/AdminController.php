<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $tickets = Ticket::query()
            ->when($request->payment_status, fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->q, fn($q) => $q->where(function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->q . '%')
                      ->orWhere('name', 'like', '%' . $request->q . '%');
            }))
            ->when($request->sort_by, fn($q) => $q->orderBy($request->sort_by))
            ->get();

        return view('admin.admin-dashboard', compact('tickets'));

    }
}
