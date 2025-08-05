@extends('layouts.app')

@section('title', 'Pembelian Tiket')

@section('content')
<div class="px-6 lg:px-16 xl:px-24 2xl:px-32 py-8">
  <div class="container py-5">

    <!-- 🔹 Tempat notifikasi alert (Tengah Atas) -->
    <div id="page-alert" class="alert alert-danger d-none text-center d-flex align-items-center justify-content-center gap-2" role="alert">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <span id="page-alert-text"></span>
    </div>

    <div class="row">
      <!-- Kiri: Poster dan Kategori Tiket -->
      <div class="col-lg-5 order-1 order-lg-1">
        <!-- Poster Event -->
        <div class="card shadow-sm border-0 mb-3">
          <img src="{{ asset('storage/' . $event->poster) }}" class="card-img-top" style="max-height: 250px; object-fit: cover;" alt="Poster">
          <div class="card-body">
            <h4 class="card-title fw-bold mb-2">{{ $event->title }}</h4>
            <p class="card-text text-muted">📍 {{ $event->location }} — 🗓️ {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</p>
            <p class="card-text">{{ $event->description }}</p>
          </div>
        </div>

        <!-- Kategori Tiket -->
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white fw-semibold fs-5">Kategori Tiket</div>
          <div class="card-body">
            @foreach ($tickets as $ticket)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
              <div>
                <div class="fw-bold">{{ $ticket->name }}</div>
                <div class="text-muted">Rp {{ number_format($ticket->price, 0, ',', '.') }}</div>
              </div>
              <div id="ticket-control">
                @if ($loop->first)
                  <!-- Kontrol dikendalikan JS -->
                @endif
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Kanan: Detail & Form -->
      <div class="col-lg-7 order-2 order-lg-2 mt-4 mt-lg-0">
        <!-- Detail Pesanan -->
        <div class="card shadow-sm border-0 mb-3">
          <div class="card-header bg-white fw-semibold fs-5">Detail Pesanan</div>
          <div class="card-body">
            <p class="mb-2"><span id="ticket-count">0</span> Tiket Dipesan</p>
            <!-- ✅ Hanya Total Bayar -->
            <p class="fw-bold border-top pt-2">Total Bayar 
              <span class="float-end">Rp <span id="total-final">0</span></span>
            </p>
          </div>
        </div>

        <!-- Form Pemesanan -->
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white fw-semibold fs-5">Form Pemesanan</div>
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

            @php
              $activeTicket = $tickets->first();
            @endphp

            @if($activeTicket)
            <form action="{{ route('ticket.store') }}" method="POST" id="ticket-form">
              @csrf
              <div class="mb-3">
                <label class="form-label">Email Pembeli 
                  <small class="text-muted d-block">📩 Tiket online akan dikirim ke email ini</small>
                </label>
                <input type="email" name="email" class="form-control" required placeholder="nama@gmail.com" value="{{ old('email') }}" />
              </div>

              <input type="hidden" name="qty" id="ticketQty" value="0">
              <input type="hidden" name="ticket_id" value="{{ $activeTicket->id }}">
              <div id="pengunjung-list"></div>
              <div class="flex justify-end">
                  <button type="submit" 
                      class="bg-gradient-to-r from-orange-500 to-yellow-400 
                            hover:from-orange-600 hover:to-yellow-500 
                            text-white font-semibold px-5 py-2 rounded-lg shadow-md 
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
  const maxQty = 3;

  function showPageAlert(message) {
      const alertBox = document.getElementById('page-alert');
      document.getElementById('page-alert-text').innerText = message;
      alertBox.classList.remove('d-none');
      setTimeout(() => alertBox.classList.add('show'), 10);
      setTimeout(() => { alertBox.classList.remove('show'); setTimeout(() => alertBox.classList.add('d-none'), 500); }, 3000);
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
      div.className = 'border rounded p-3 mb-3 position-relative pengunjung-entry';
      div.setAttribute('data-index', i);
      div.innerHTML = `
        <button type="button" class="btn btn-sm position-absolute top-0 end-0 m-2" style="background-color: rgba(0,0,0,0.6);" onclick="removePengunjung(${i})" title="Hapus pengunjung">
          <i class="bi bi-trash text-white"></i>
        </button>
        <p class="fw-semibold mb-3">Data Pengunjung ${i + 1}</p>
        <div class="mb-3">
          <label class="form-label">Nama Lengkap:</label>
          <input type="text" name="name[]" required class="form-control" placeholder="Nama sesuai KTP" />
          <small class="text-muted">Pastikan sesuai identitas resmi</small>
        </div>
        <div>
          <label class="form-label">No. Telepon:</label>
          <input type="text" name="phone[]" required class="form-control" placeholder="08xxxxxxxxxx" />
          <small class="text-muted">Nomor ini digunakan jika kami perlu menghubungi Anda</small>
        </div>`;
      wrapper.appendChild(div);
    }
  }

  function renderTicketControls(qty) {
    const control = document.getElementById('ticket-control');
    if (qty > 0) {
      control.innerHTML = `<div class="d-flex align-items-center"><button type="button" class="btn btn-sm btn-outline-secondary" onclick="decreaseTicket()">−</button><span class="mx-2">${qty}</span><button type="button" class="btn btn-sm btn-outline-secondary" onclick="addTicket()">+</button></div>`;
    } else {
      control.innerHTML = `<button type="button" class="btn btn-sm btn-outline-primary" onclick="addTicket()">Tambah</button>`;
    }
  }

  function addTicket() {
    let qty = parseInt(document.getElementById('ticketQty').value || 0);
    if (qty + 1 > maxQty) { showPageAlert("Maksimal pembelian hanya 3 tiket per transaksi."); return; }
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
      const updatedEntries = document.querySelectorAll('.pengunjung-entry');
      updatedEntries.forEach((entry, newIndex) => {
          entry.setAttribute('data-index', newIndex);
          entry.querySelector('p').textContent = `Data Pengunjung ${newIndex + 1}`;
          entry.querySelector('button').setAttribute('onclick', `removePengunjung(${newIndex})`);
      });
      const qty = updatedEntries.length;
      document.getElementById('ticketQty').value = qty;
      updateSummary(qty);
      renderTicketControls(qty);
  }


  window.onload = function() { renderTicketControls(0); };
</script>

<style>
#page-alert { position: fixed; top: -80px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 350px; opacity: 0; transition: all 0.5s ease; }
#page-alert.show { top: 20px; opacity: 1; }
</style>
@endif
@endsection
