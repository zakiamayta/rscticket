<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticket Registration System</title>
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Inter:wght@400;500;700;900&family=Noto+Sans:wght@400;500;700;900" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>
<body class="bg-white font-[Inter,'Noto Sans',sans-serif] min-h-screen overflow-x-hidden">
  <div class="flex flex-col min-h-screen">
    {{-- Header --}}
    <header class="flex items-center justify-between border-b px-10 py-3">
      <div class="flex items-center gap-4 text-[#111418]">
        <div class="size-4">
          {{-- Logo --}}
          <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M24 45.8096C19.6865 45.8096 15.4698 44.5305 11.8832 42.134C8.29667 39.7376 5.50128 36.3314 3.85056 32.3462C2.19985 28.361 1.76794 23.9758 2.60947 19.7452C3.451 15.5145 5.52816 11.6284 8.57829 8.5783C11.6284 5.52817 15.5145 3.45101 19.7452 2.60948C23.9758 1.76795 28.361 2.19986 32.3462 3.85057C36.3314 5.50129 39.7376 8.29668 42.134 11.8833C44.5305 15.4698 45.8096 19.6865 45.8096 24L24 24L24 45.8096Z" fill="currentColor"></path>
          </svg>
        </div>
        <h2 class="text-lg font-bold">Ticket Registration System</h2>
      </div>
      {{-- <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-[#0c7ff2] text-white px-4 py-2 rounded-lg font-bold text-sm">Logout</button>
      </form> --}}
    </header>

    {{-- Content --}}
    <div class="flex flex-1 justify-center px-40 py-5">
      <div class="w-full max-w-[960px]">
        <h2 class="text-[28px] font-bold px-4 pt-5 pb-3">Ticket Registrations</h2>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap-4 px-4 py-3 max-w-[480px]">
          <select name="payment_status" class="form-input w-full h-14 border rounded-lg p-3">
            <option value="">-- Semua Status --</option>
            <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
            <option value="Unpaid" {{ request('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="Pending" {{ request('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
          </select>

          <select name="sort_by" class="form-input w-full h-14 border rounded-lg p-3">
            <option value="">-- Default --</option>
            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
            <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
          </select>

          <div class="w-full">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Email or Name" class="form-input w-full h-12 rounded-lg bg-[#f0f2f5] px-4" />
          </div>

          <div class="flex gap-2">
            <button type="submit" class="bg-[#0c7ff2] text-white px-4 py-2 rounded-lg font-bold text-sm">Filter</button>
            <a href="{{ route('admin.dashboard') }}" class="bg-[#f0f2f5] px-4 py-2 rounded-lg font-bold text-sm">Refresh</a>
          </div>
        </form>

        Export Button --}}
        {{-- <div class="px-4 py-3">
          <a href="{{ route('admin.export') }}" class="bg-[#f0f2f5] px-4 py-2 rounded-lg font-bold text-sm">Export</a>
        </div>

        {{-- Data Table --}}
        <div class="px-4 py-3">
          <div class="overflow-auto rounded-lg border border-[#dbe0e6] bg-white">
            <table class="min-w-full table-auto">
              <thead>
                <tr class="bg-white text-[#111418] text-sm font-medium">
                  <th class="px-4 py-3 text-left">Email</th>
                  <th class="px-4 py-3 text-left">Name</th>
                  <th class="px-4 py-3 text-left">Phone</th>
                  <th class="px-4 py-3 text-left">Checkout Time</th>
                  <th class="px-4 py-3 text-left">Paid Time</th>
                  <th class="px-4 py-3 text-left">Payment Status</th>
                  <th class="px-4 py-3 text-left">Total Amount</th>
                  <th class="px-4 py-3 text-left">QR Code</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($tickets as $ticket)
                  <tr class="border-t text-[#60758a] text-sm">
                    <td class="px-4 py-2">{{ $ticket->email }}</td>
                    <td class="px-4 py-2">{{ $ticket->name }}</td>
                    <td class="px-4 py-2">{{ $ticket->phone }}</td>
                    <td class="px-4 py-2">{{ $ticket->checkout_time }}</td>
                    <td class="px-4 py-2">{{ $ticket->paid_time }}</td>
                    <td class="px-4 py-2">
                      <span class="inline-block bg-[#f0f2f5] px-3 py-1 rounded-lg text-[#111418] font-medium">
                        {{ $ticket->payment_status }}
                      </span>
                    </td>
                    <td class="px-4 py-2">Rp{{ number_format($ticket->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 font-bold text-blue-600">
                      <a href="{{ route('admin.qr', $ticket->id) }}">View QR</a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-4 text-[#60758a]">No tickets found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

