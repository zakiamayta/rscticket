@extends('layouts.admin')

@section('title', 'Pantauan Absensi')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Pantauan Absensi Pengunjung</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-md">
            <thead class="bg-gray-100 text-left text-sm font-semibold">
                <tr>
                    <th class="px-4 py-3 border-b">No</th>
                    <th class="px-4 py-3 border-b">ID Transaksi</th>
                    <th class="px-4 py-3 border-b">Email</th>
                    <th class="px-4 py-3 border-b">Registered At</th>
                    <th class="px-4 py-3 border-b">Status Absen</th>
                    <th class="px-4 py-3 border-b">QR Code</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse ($attendees as $index => $attendee)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $attendee->transaction?->id ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $attendee->transaction?->email ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($attendee->transaction?->registered_at)
                                {{ \Carbon\Carbon::parse($attendee->transaction->registered_at)->format('d M Y H:i') }}
                            @elseif($attendee->registered_at)
                                {{ $attendee->registered_at->format('d M Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($attendee->transaction?->is_registered)
                                <span class="text-green-600 font-semibold">Sudah Absen</span>
                            @else
                                <span class="text-red-600 font-semibold">Belum Absen</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($attendee->transaction?->qr_code)
                                <a href="{{ route('guests.qr', $attendee->transaction->id) }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    QR
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-y-2">
                            @if ($attendee->transaction)
                                <button onclick="showDetail({{ $attendee->id }})"
                                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-sm rounded-md text-center">
                                    Detail Pembeli
                                </button>
                            @else
                                <span class="text-xs text-red-500">Data transaksi tidak ditemukan</span>
                            @endif

                            @if (! $attendee->transaction?->is_registered)
                                <form method="POST" action="{{ route('admin.absensi.manual', ['id' => $attendee->id]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1 text-sm rounded-md">
                                        Absen Manual
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.absensi.batal', ['id' => $attendee->id]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded-md">
                                        Batalkan Absen
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-4 py-6 text-gray-500">Tidak ada data peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50">
        <div id="modalContentWrapper" class="bg-white p-6 rounded-lg shadow-xl max-w-lg w-full transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Detail Pembeli Tiket</h3>
            <div id="modalContent" class="max-h-80 overflow-y-auto text-sm"></div>
            <button onclick="closeModal()" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-semibold transition-colors">
                Tutup
            </button>
        </div>
    </div>

    <script>
        const attendeeData = @json($attendees);

        function escapeHtml(str) {
            if (!str) return '-';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        function showDetail(attendeeId) {
            const attendee = attendeeData.find(a => a.id === attendeeId);

            if (!attendee) {
                document.getElementById('modalContent').innerHTML =
                    '<p class="text-gray-500 italic text-center">Data peserta tidak ditemukan.</p>';
            } else {
                const t = attendee.transaction ?? {};
                const html = `
                    <table class="min-w-full text-sm">
                        <tr><td class="font-semibold pr-2 py-1">Email:</td><td>${escapeHtml(t.email)}</td></tr>
                        <tr><td class="font-semibold pr-2 py-1">Waktu Checkout:</td><td>${t.checkout_time ? new Date(t.checkout_time).toLocaleString() : '-'}</td></tr>
                        <tr><td class="font-semibold pr-2 py-1">Status Bayar:</td><td>${escapeHtml(t.payment_status)}</td></tr>
                        <tr><td class="font-semibold pr-2 py-1">Nama Peserta:</td><td>${escapeHtml(attendee.name)}</td></tr>
                        <tr><td class="font-semibold pr-2 py-1">No HP:</td><td>${escapeHtml(attendee.phone_number)}</td></tr>
                        <tr><td class="font-semibold pr-2 py-1">ID Tiket:</td><td>${escapeHtml(attendee.ticket_id)}</td></tr>
                    </table>
                `;
                document.getElementById('modalContent').innerHTML = html;
            }

            const modal = document.getElementById('detailModal');
            const modalContent = document.getElementById('modalContentWrapper');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            const modalContent = document.getElementById('modalContentWrapper');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
@endsection
