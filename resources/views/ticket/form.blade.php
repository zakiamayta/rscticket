@extends('layouts.app')

@section('title', 'Pembelian Tiket')

@section('content')
<div class="container py-5">
  <div class="row">
    <!-- Kategori Tiket + Poster -->
    <div class="col-lg-5 mb-4">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white fw-semibold fs-5">Kategori Tiket</div>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
            <div>
              <div class="fw-bold">Early Bird</div>
              <div class="text-muted">Rp 50.000</div>
            </div>
            <div id="ticket-control"></div>
          </div>
        </div>
      </div>

      <!-- Poster -->
      <div class="card shadow-sm border-0">
        <img src="{{ asset('poster2.jpg') }}" class="card-img-top" style="max-height: 250px; object-fit: cover;" alt="Poster">
        <div class="card-body">
          <h4 class="card-title fw-bold mb-2">Negative Mental Attitude</h4>
          <p class="card-text text-muted">📍 Kediri — 🗓️ 19 Oktober 2025</p>
          <p class="card-text">Sebuah konser musik dengan semangat hardcore punk untuk menyuarakan suara hati pemuda.</p>
        </div>
      </div>
    </div>

    <!-- Detail & Form -->
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white fw-semibold fs-5">Detail Pesanan</div>
        <div class="card-body">
          <p class="mb-2"><span id="ticket-count">0</span> Tiket Dipesan</p>
          <p class="fw-bold">Total <span class="float-end">Rp <span id="total-price">0</span></span></p>
          <p class="fw-bold">Admin Fee <span class="float-end">Rp 2.500</span></p>
          <p class="fw-bold border-top pt-2">Total Bayar 
            <span class="float-end">Rp <span id="total-final">2.500</span></span>
          </p>

        </div>
      </div>

      <!-- Form -->
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

          <form action="{{ route('ticket.store') }}" method="POST" id="ticket-form">
            @csrf
            <div class="mb-3">
              <label class="form-label">Email Pembeli</label>
              <input type="email" name="email" class="form-control" required placeholder="nama@gmail.com" value="{{ old('email') }}" />
            </div>

            <input type="hidden" name="qty" id="ticketQty" value="0">
            <div id="pengunjung-list"></div>

            <button type="submit" class="btn btn-primary w-100 mt-3" id="checkout-btn" disabled>
              Checkout & Lanjut ke Pembayaran
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="deleteModalMessage">Apakah Anda yakin ingin menghapus data?</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
        </div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
  const ticketPrice = 50000;

  function updateSummary(qty) {
    const adminFee = 2500;
    const subtotal = qty * ticketPrice;
    const total = subtotal + adminFee;

    document.getElementById('ticket-count').innerText = qty;
    document.getElementById('total-price').innerText = subtotal.toLocaleString();
    document.getElementById('total-final').innerText = total.toLocaleString();
    document.getElementById('checkout-btn').disabled = qty === 0;
  }


  function updateFormFields(qty) {
    const wrapper = document.getElementById('pengunjung-list');
    const current = wrapper.querySelectorAll('.pengunjung-entry').length;
    for (let i = current; i < qty; i++) {
      const div = document.createElement('div');
      div.className = 'border rounded p-3 mb-3 position-relative pengunjung-entry';
      div.innerHTML = `
        <button type="button" class="btn position-absolute top-0 end-0 m-2 text-danger delete-btn">
          <i class="fas fa-trash"></i>
        </button>
        <p class="fw-semibold mb-2">Data Pengunjung ${i + 1}</p>
        <div class="mb-2">
          <label class="form-label">Nama Lengkap:</label>
          <input type="text" name="name[]" required class="form-control" placeholder="Nama sesuai KTP" />
        </div>
        <div>
          <label class="form-label">No. Telepon:</label>
          <input type="text" name="phone[]" required class="form-control" placeholder="08xxxxxxxxxx" />
        </div>
      `;
      wrapper.appendChild(div);
    }
    attachDeleteHandlers();
  }


  function renderTicketControls(qty) {
    const control = document.getElementById('ticket-control');
    if (qty > 0) {
      control.innerHTML = `
        <div class="d-flex align-items-center">
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="decreaseTicket()">−</button>
          <span class="mx-2">${qty}</span>
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addTicket()">+</button>
        </div>
      `;
    } else {
      control.innerHTML = `
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTicket()">Tambah</button>
      `;
    }
  }

  function addTicket() {
    hideError();
    let qty = parseInt(document.getElementById('ticketQty').value || 0);
    if (qty >= 3) {
      showError('Maksimal hanya boleh memesan 3 tiket dalam 1 transaksi.');
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
    if (qty > 1) {
      qty -= 1;
      document.getElementById('ticketQty').value = qty;
      updateSummary(qty);
      renderTicketControls(qty);
      updateFormFields(qty);
    } else {
      showError('Minimal harus ada 1 tiket.');
    }
  }

  function showError(message) {
    const errorDiv = document.getElementById('ticket-error');
    errorDiv.innerText = message;
    errorDiv.classList.remove('d-none');
  }

  function hideError() {
    const errorDiv = document.getElementById('ticket-error');
    errorDiv.innerText = '';
    errorDiv.classList.add('d-none');
  }

  let deleteIndex = null;

  function confirmDeleteVisitor(index) {
    const wrapper = document.getElementById('pengunjung-list');
    const pengunjungs = wrapper.querySelectorAll('.pengunjung-entry');
    const nameInput = pengunjungs[index]?.querySelector('input[name="name[]"]');
    const name = nameInput?.value || 'pengunjung ini';
    deleteIndex = index;
    document.getElementById('deleteModalMessage').innerText =
      `Apakah Anda yakin ingin menghapus data ${name}?`;
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
  }

  document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (deleteIndex !== null) {
      actuallyRemoveVisitor(deleteIndex);
      deleteIndex = null;
    }
    bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
  });

  function actuallyRemoveVisitor(index) {
    const wrapper = document.getElementById('pengunjung-list');
    const pengunjungs = wrapper.querySelectorAll('.pengunjung-entry');

    if (pengunjungs.length <= 1) {
      showError('Minimal harus ada 1 tiket.');
      return;
    }

    pengunjungs[index]?.remove();

    const remaining = wrapper.querySelectorAll('.pengunjung-entry').length;
    document.getElementById('ticketQty').value = remaining;
    updateSummary(remaining);
    renderTicketControls(remaining);
    attachDeleteHandlers();
    reindexVisitors(); 
  }

    window.onload = () => {
    const oldNames = @json(old('name'));
    const oldPhones = @json(old('phone'));

    if (oldNames && oldNames.length > 0) {
      document.getElementById('ticketQty').value = oldNames.length;
      updateSummary(oldNames.length);
      renderTicketControls(oldNames.length);

      const wrapper = document.getElementById('pengunjung-list');
      for (let i = 0; i < oldNames.length; i++) {
        const div = document.createElement('div');
        div.className = 'border rounded p-3 mb-3 position-relative pengunjung-entry';
        div.innerHTML = `
          <button type="button" class="btn position-absolute top-0 end-0 m-2 text-danger delete-btn">
            <i class="fas fa-trash"></i>
          </button>
          <p class="fw-semibold mb-2">Data Pengunjung ${i + 1}</p>
          <div class="mb-2">
            <label class="form-label">Nama Lengkap:</label>
            <input type="text" name="name[]" required class="form-control" value="${oldNames[i]}" placeholder="Nama sesuai KTP" />
          </div>
          <div>
            <label class="form-label">No. Telepon:</label>
            <input type="text" name="phone[]" required class="form-control" value="${oldPhones[i] ?? ''}" placeholder="08xxxxxxxxxx" />
          </div>
        `;
        wrapper.appendChild(div);
      }
      attachDeleteHandlers();
    } else {
      renderTicketControls(0);
      attachDeleteHandlers();
    }
  };

  function attachDeleteHandlers() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach((btn, idx) => {
      btn.onclick = () => confirmDeleteVisitor(idx);
    });
  }
  function reindexVisitors() {
    const wrapper = document.getElementById('pengunjung-list');
    const pengunjungs = wrapper.querySelectorAll('.pengunjung-entry');
    pengunjungs.forEach((entry, index) => {
      const label = entry.querySelector('p.fw-semibold');
      label.innerText = `Data Pengunjung ${index + 1}`;
    });
  }

</script>
@endsection
