@extends('layouts.app')

@section('title', 'Pembelian Tiket Konser')

@section('content')
<div class="container py-4">

  <!-- Tombol Kembali -->
  <div class="mb-3">
    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">← Kembali ke Home</a>
  </div>

  <!-- Poster Event -->
  <div class="text-center mb-4">
    <img src="{{ asset('poster2.jpg') }}" alt="Poster Konser" class="rounded-lg shadow w-100" style="max-height: 450px; object-fit: cover;">
  </div>

  <!-- Detail Event -->
  <div class="mb-5">
    <h1 class="fw-bold" style="font-size: 2rem;">Konser: Negative Mental Attitude</h1>
    <p class="text-muted mb-1">📍 Kediri</p>
    <p class="text-muted mb-0">🗓️ Oktober 2025</p>
  </div>

  <!-- Grid: Form & Ringkasan -->
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <!-- Qty Selector -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Jumlah Tiket:</label>
        <div class="d-flex align-items-center">
          <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="updateQty(-1)">-</button>
          <input type="number" id="ticketQty" value="1" min="1" class="form-control text-center" style="width: 60px;" readonly>
          <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="updateQty(1)">+</button>
        </div>
      </div>

      <!-- Form -->
      <div class="bg-white p-4 rounded shadow-sm border">
        <h2 class="h5 fw-bold text-orange-600 mb-3">Form Pembelian Tiket</h2>

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

        <form action="{{ route('ticket.store') }}" method="POST">
          @csrf

          <!-- Email -->
          <div class="mb-4">
            <label class="form-label">Email Pembeli:</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="contoh: nama@email.com" />
          </div>

          <!-- Dynamic Pengunjung -->
          <div id="pengunjung-list"></div>

          <!-- Submit -->
          <div class="mt-4">
            <button type="submit" class="btn w-100 text-white fw-semibold" style="background-color: #ea580c;">
              Lanjut ke Pembayaran
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4 mt-4 mt-lg-0">
      <div class="bg-light p-4 rounded shadow-sm border">
        <h5 class="fw-bold text-orange-600 mb-3">Ringkasan Pesanan</h5>
        <p class="mb-1">Harga Tiket: <span class="float-end">Rp <span id="ticket-price">50000</span></span></p>
        <p class="mb-1">Jumlah Tiket: <span class="float-end" id="ticket-count">1</span></p>
        <hr>
        <p class="fw-semibold">Total: <span class="float-end">Rp <span id="total-price">50000</span></span></p>
      </div>
    </div>
  </div>
</div>

<script>
  const ticketPrice = 50000;

  function updateQty(change) {
    const qtyInput = document.getElementById('ticketQty');
    let qty = parseInt(qtyInput.value) || 1;
    qty += change;
    if (qty < 1) qty = 1;
    qtyInput.value = qty;
    updateSummary();
    updateFormFields(qty);
  }

  function updateSummary() {
    const qty = parseInt(document.getElementById('ticketQty').value) || 1;
    document.getElementById('ticket-count').innerText = qty;
    document.getElementById('total-price').innerText = (ticketPrice * qty).toLocaleString();
  }

  function updateFormFields(qty) {
    const wrapper = document.getElementById('pengunjung-list');
    const currentData = [];
    wrapper.querySelectorAll('.pengunjung').forEach((el) => {
      currentData.push({
        name: el.querySelector('input[name="name[]"]').value,
        phone: el.querySelector('input[name="phone[]"]').value
      });
    });

    wrapper.innerHTML = '';
    for (let i = 0; i < qty; i++) {
      const data = currentData[i] || {name: '', phone: '' };
      addPengunjung(data.name, data.phone);
    }
  }

  function addPengunjung(name = '', phone = '') {
    const wrapper = document.getElementById('pengunjung-list');
    const count = wrapper.querySelectorAll('.pengunjung').length + 1;

    const div = document.createElement('div');
    div.className = 'pengunjung bg-white p-3 rounded border mb-3';
    div.innerHTML = `
      <h6 class="fw-bold text-orange-600 mb-3">Data Pengunjung ${count}</h6>
      <div class="mb-2">
        <label class="form-label">Nama Lengkap:</label>
        <input type="text" name="name[]" value="${name}" required class="form-control" placeholder="Nama sesuai KTP" />
      </div>
      <div class="mb-2">
        <label class="form-label">No. Telepon:</label>
        <input type="text" name="phone[]" value="${phone}" class="form-control" required placeholder="08xxxxxxxxxx" />
      </div>
      <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="this.parentElement.remove(); updateAfterRemove()">Hapus Pengunjung</button>
    `;
    wrapper.appendChild(div);
  }

  function updateAfterRemove() {
    const forms = document.querySelectorAll('#pengunjung-list .pengunjung');
    document.getElementById('ticketQty').value = forms.length;
    forms.forEach((el, i) => {
      const title = el.querySelector('h6');
      if (title) title.textContent = `Data Pengunjung ${i + 1}`;
    });
    updateSummary();
  }

  window.addEventListener('DOMContentLoaded', () => {
    @if(old('name'))
      @foreach(old('name') as $i => $name)
        addPengunjung(
          @json(old('name')[$i] ?? ''),
          @json(old('phone')[$i] ?? '')
        );
      @endforeach
    @else
      updateFormFields(1);
    @endif
    updateSummary();
  });
</script>
@endsection
