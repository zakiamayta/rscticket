<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>
<body class="bg-white font-[Inter,sans-serif] min-h-screen">

    {{-- Header --}}
    <header class="bg-white border-b shadow-sm px-10 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-6 h-6 text-blue-600">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 45.8096C19.6865 45.8096 15.4698 44.5305 11.8832 42.134C8.29667 39.7376 5.50128 36.3314 3.85056 32.3462C2.19985 28.361 1.76794 23.9758 2.60947 19.7452C3.451 15.5145 5.52816 11.6284 8.57829 8.5783C11.6284 5.52817 15.5145 3.45101 19.7452 2.60948C23.9758 1.76795 28.361 2.19986 32.3462 3.85057C36.3314 5.50129 39.7376 8.29668 42.134 11.8833C44.5305 15.4698 45.8096 19.6865 45.8096 24L24 24L24 45.8096Z" fill="currentColor"></path>
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-gray-800">Admin Dashboard</h1>
        </div>
        <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
        Logout
    </button>
</form>

    </header>

    

    {{-- Main Content --}}
    <main class="px-10 py-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Transaction List</h2>

<form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap-4 mb-6">

  <input type="date" name="start_date" class="form-input w-48 border p-2 rounded" value="{{ request('start_date') }}">
  <input type="date" name="end_date" class="form-input w-48 border p-2 rounded" value="{{ request('end_date') }}">

  <select name="payment_status" class="form-input w-48 border p-2 rounded">
    <option value="">-- Semua Status --</option>
    <option value="paid" @if(request('payment_status') === 'paid') selected @endif>Paid</option>
    <option value="unpaid" @if(request('payment_status') === 'unpaid') selected @endif>Unpaid</option>
  </select>

  <select name="sort_by" class="form-input w-48 border p-2 rounded">
    <option value="">-- Sortir Berdasarkan --</option>
    <option value="email" @if(request('sort_by') === 'email') selected @endif>Email</option>
    <option value="name" @if(request('sort_by') === 'name') selected @endif>Name</option>
    <option value="payment_status" @if(request('sort_by') === 'payment_status') selected @endif>Status</option>
    <option value="checkout_time" @if(request('sort_by') === 'checkout_time') selected @endif>Checkout Time</option>
  </select>

  <input type="text" name="q" placeholder="Cari email/nama" class="form-input w-64 border p-2 rounded" value="{{ request('q') }}"/>

  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
  <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded bg-gray-200">Reset</a>

  <div class="flex gap-4 mb-4">
    <a href="{{ route('admin.dashboard.export.excel', request()->query()) }}"
       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition text-sm">Export Excel</a>

    <a href="{{ route('admin.dashboard.export.pdf', request()->query()) }}"
       class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm" target="_blank">Export PDF</a>
</div>


</form>

        <div class="bg-white border rounded-xl shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-600 font-semibold">
                    <tr>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Phone Number</th>
                        <th class="px-4 py-3">Checkout Time</th>
                        <th class="px-4 py-3">Paid Time</th>
                        <th class="px-4 py-3">Payment Status</th>
                        <th class="px-4 py-3">Total Amount</th>
                        <th class="px-4 py-3">QR Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($transactions as $transaction)
                        @foreach ($transaction->details as $detail)
                            <tr class="hover:bg-gray-50 text-gray-700">
                                <td class="px-4 py-2">{{ $transaction->email }}</td>
                                <td class="px-4 py-2">{{ $detail->name }}</td>
                                <td class="px-4 py-2">{{ $detail->phone_number }}</td>
                                <td class="px-4 py-2">{{ $transaction->checkout_time }}</td>
                                <td class="px-4 py-2">{{ $transaction->paid_time ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-medium 
                                        {{ $transaction->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    @if($transaction->qris_url)
                                        <a href="{{ $transaction->qris_url }}" target="_blank" class="text-blue-600 hover:underline font-medium">View QR</a>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-6">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
