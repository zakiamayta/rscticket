@extends('layouts.app')

@section('title', 'Pembelian Tiket')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-8">
  <div class="container py-5">

    <!-- 🔹 Notifikasi Alert (Floating) -->
    <div id="page-alert" class="alert alert-danger d-none text-center d-flex align-items-center justify-content-center gap-2 shadow-lg rounded-lg" role="alert">
      <i class="bi bi-exclamation-triangle-fill fs-5"></i>
      <span id="page-alert-text"></span>
    </div>

    <div class="row g-4">
      <!-- Kiri: Poster & Kategori Tiket -->
      <div class="col-lg-5">
        <!-- Poster Event -->
        <div class="card shadow-sm border-0 mb-3 overflow-hidden rounded-3">
          <img src="{{ asset('storage/' . $event->poster) }}" class="card-img-top" style="max-height: 250px; object-fit: cover;" alt="Poster Event">
          <div class="card-body">
            <h4 class="card-title fw-bold text-orange-600 mb-2">{{ $event->title }}</h4>
            <p class="card-text text-muted mb-1">
              📍 {{ $event->location }}  
              — 🗓️ {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}
            </p>
            <p class="card-text small text-gray-600">{{ $event->description }}</p>
          </div>
        </div>

        <!-- Kategori Tiket -->
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-header bg-white fw-semibold fs-5 text-orange-600">Kategori Tiket</div>
          <div class="card-body">
            @foreach ($tickets as $ticket)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
              <div>
                <div class="fw-bold">{{ $ticket->name }}</div>
                <div class="text-muted">Rp {{ number_format($ticket->price, 0, ',', '.') }}</div>
              </div>
              <div id="ticket-control">
                @if ($loop->first)
                  <!-- Kontrol tiket dikendalikan JS -->
                @endif
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Kanan: Detail & Form -->
      <div class="col-lg-7">
        <!-- Detail Pesanan -->
        <div class="card shadow-sm border-0 mb-3 rounded-3">
          <div class="card-header bg-white fw-semibold fs-5 text-orange-600">Detail Pesanan</div>
          <div class="card-body">
            <p class="mb-2"><span id="ticket-count">0</span> Tiket Dipesan</p>
            <p class="fw-bold border-top pt-2 mb-0">
              Total Bayar 
              <span class="float-end">Rp <span id="total-final">0</span></span>
            </p>
          </div>
        </div>

        <!-- Form Pemesanan -->
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-header bg-white fw-semibold fs-5 text-orange-600">Form Pemesanan</div>
          <div class="card-body">
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                  @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div id="ticket-error" class="alert alert-danger d-none"></div>

            @php $activeTicket = $tickets->first(); @endphp

            @if($activeTicket)
            <form action="{{ route('ticket.store') }}" method="POST" id="ticket-form">
              @csrf
              <div class="mb-3">
                <label class="form-label">Email Pembeli 
                  <small class="text-muted d-block">📩 Tiket online akan dikirim ke email ini</small>
                </label>
                <input type="email" name="email" class="form-control rounded-pill" required placeholder="nama@gmail.com" value="{{ old('email') }}" />
              </div>

              <input type="hidden" name="qty" id="ticketQty" value="0">
              <input type="hidden" name="ticket_id" value="{{ $activeTicket->id }}">

              <!-- List Data Pengunjung -->
              <div id="pengunjung-list"></div>

              <div class="text-end">
                <button type="submit" 
                    class="bg-gradient-to-r from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 
                          text-white font-semibold px-5 py-2 rounded-pill shadow-md 
                          transition transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    id="checkout-btn" disabled>
                    Checkout
                </button>
              </div>
            </form>
            @else
              <div class="alert alert-warning">Tiket belum tersedia saat ini.</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@if($activeTicket)
  <script>
    const ticketPrice = {{ $activeTicket->price }};
    const maxQty = {{ $event->max_tickets_per_email }}; // 🔹 Dinamis dari event
    let currentQty = 0;

    function showPageAlert(message) {
      const alertBox = document.getElementById('page-alert');
      document.getElementById('page-alert-text').innerText = message;
      alertBox.classList.remove('d-none');
      setTimeout(() => alertBox.classList.add('show'), 10);
      setTimeout(() => {
        alertBox.classList.remove('show');
        setTimeout(() => alertBox.classList.add('d-none'), 500);
      }, 3000);
    }

    function updateSummary(qty) {
      const total = qty * ticketPrice;
      document.getElementById('ticket-count').innerText = qty;
      document.getElementById('total-final').innerText = total.toLocaleString();
      document.getElementById('checkout-btn').disabled = qty === 0;
    }

    function updateFormFields(qty) {
      const wrapper = document.getElementById('pengunjung-list');
      wrapper.innerHTML = '';
      for (let i = 0; i < qty; i++) {
        const div = document.createElement('div');
        div.className = 'border rounded-4 p-3 mb-3 position-relative pengunjung-entry shadow-sm fade-in';
        div.setAttribute('data-index', i);
        div.innerHTML = `
          <button type="button" class="btn btn-sm position-absolute top-0 end-0 m-2 rounded-circle bg-danger text-white shadow-sm trash-btn" 
                  onclick="removePengunjung(${i})" title="Hapus pengunjung">
            <i class="bi bi-trash"></i>
          </button>
          <p class="fw-semibold mb-3 text-orange-600">Data Pengunjung ${i + 1}</p>
          <div class="mb-3">
            <label class="form-label">Nama Lengkap:</label>
            <input type="text" name="name[]" required class="form-control rounded-pill" placeholder="Nama sesuai KTP" />
          </div>
          <div>
            <label class="form-label">No. Telepon:</label>
            <input type="text" name="phone[]" class="form-control rounded-pill" placeholder="08xxxxxxxxxx" />
          </div>`;
        wrapper.appendChild(div);
      }
    }

    function renderTicketControls(qty) {
      const control = document.getElementById('ticket-control');
      if (maxQty === 1) {
        // Event hanya 1 tiket per email → tombol +/− hilang
        control.innerHTML = `<span class="fw-bold">1</span>`;
      } else if (qty > 0) {
        control.innerHTML = `
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-orange-circle" onclick="decreaseTicket()"><i class="bi bi-dash-lg"></i></button>
            <span class="fw-bold">${qty}</span>
            <button type="button" class="btn btn-sm btn-orange-circle" onclick="addTicket()"><i class="bi bi-plus-lg"></i></button>
          </div>`;
      } else {
        control.innerHTML = `<button type="button" class="btn btn-sm btn-orange-pill" onclick="addTicket()"><i class="bi bi-plus-lg me-1"></i>Tambah</button>`;
      }
    }

    function addTicket() {
      let qty = parseInt(document.getElementById('ticketQty').value || 0);
      if (qty + 1 > maxQty) { 
        showPageAlert(`Maksimal pembelian hanya ${maxQty} tiket per transaksi.`); 
        return; 
      }
      qty += 1;
      document.getElementById('ticketQty').value = qty;
      updateSummary(qty);
      renderTicketControls(qty);
      updateFormFields(qty);
    }

    function decreaseTicket() {
      let qty = parseInt(document.getElementById('ticketQty').value || 0);
      if (qty <= 1) { 
        showPageAlert("Minimal 1 tiket harus dipesan."); 
        return; 
      }
      qty -= 1;
      document.getElementById('ticketQty').value = qty;
      updateSummary(qty);
      renderTicketControls(qty);
      updateFormFields(qty);
    }

    function removePengunjung(index) {
      const entries = document.querySelectorAll('.pengunjung-entry');
      if (entries.length <= 1) { 
        showPageAlert("Minimal 1 tiket harus dipesan."); 
        return; 
      }
      entries[index].remove();
      const qty = document.querySelectorAll('.pengunjung-entry').length;
      document.getElementById('ticketQty').value = qty;
      updateSummary(qty);
      renderTicketControls(qty);
    }

    window.onload = () => {
      const initialQty = maxQty === 1 ? 1 : 0; // langsung isi 1 kalau max hanya 1
      document.getElementById('ticketQty').value = initialQty;
      updateSummary(initialQty);
      renderTicketControls(initialQty);
      if (initialQty > 0) updateFormFields(initialQty);
    }
  </script>


<style>
  /* 🔹 Alert */
  #page-alert {
    position: fixed;
    top: -80px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    min-width: 350px;
    opacity: 0;
    transition: all 0.5s ease;
  }
  #page-alert.show {
    top: 20px;
    opacity: 1;
  }

  /* 🔹 Animasi masuk/keluar form */
  .fade-in { animation: fadeIn 0.3s ease-in-out; }
  .fade-out { animation: fadeOut 0.3s ease-in-out forwards; }
  @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
  @keyframes fadeOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(0.95); } }

  /* 🔹 Tombol + dan - */
  .btn-orange-circle {
    background: linear-gradient(to right, #f97316, #fbbf24);
    border: none;
    color: white;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .btn-orange-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(249, 115, 22, 0.3);
  }

  /* 🔹 Tombol Tambah */
  .btn-orange-pill {
    background: linear-gradient(to right, #f97316, #fbbf24);
    border: none;
    color: white;
    border-radius: 50px;
    padding: 6px 14px;
    font-weight: 500;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .btn-orange-pill:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(249, 115, 22, 0.3);
  }

  /* 🔹 Tombol trash */
  .trash-btn {
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  .trash-btn:hover {
    background-color: #dc2626;
    transform: scale(1.1);
  }
</style>
@endif
@endsection
