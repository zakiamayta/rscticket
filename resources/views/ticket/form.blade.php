@extends('layouts.app')

@section('title', 'Pembelian Tiket Konser')

@section('content')
<div class="container py-5">
  <div class="text-center mb-4">
    <img src="{{ asset('logo.png') }}" alt="Logo Perusahaan" class="mx-auto mb-2" style="max-height: 100px;">
    <h1 class="h5 fw-bold">RSC E-Ticket</h1>
  </div>

  <div class="mx-auto bg-white p-5 rounded shadow-sm" style="max-width: 700px;">
    <h2 class="h5 fw-semibold mb-4">Form Pembelian Tiket</h2>

    {{-- Notifikasi Error --}}
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

    {{-- Form --}}
    <form action="{{ route('ticket.store') }}" method="POST">
      @csrf

      <!-- Email -->
      <div class="mb-4">
        <label class="form-label">Email Pembeli:</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="contoh: nama@email.com" />
      </div>

      <!-- Dynamic Pengunjung -->
      <div id="pengunjung-list"></div>

      <!-- Tambah Pengunjung -->
      <div class="mb-3">
        <button type="button" onclick="addPengunjung()" class="btn btn-outline-success btn-sm">+ Tambah Pengunjung</button>
      </div>

      <!-- Submit -->
      <div class="mt-4">
        <button type="submit" class="btn btn-primary w-100">Lanjut ke Pembayaran</button>
      </div>
    </form>
  </div>
</div>

<script>
  function addPengunjung(nik = '', name = '', phone = '') {
    const wrapper = document.getElementById('pengunjung-list');
    const div = document.createElement('div');
    div.className = 'pengunjung bg-white p-4 rounded-lg shadow-sm border mb-4';
    div.innerHTML = `
      <div class="mb-3">
        <label class="form-label">NIK:</label>
        <input type="text" name="nik[]" value="${nik}" required class="form-control" placeholder="Nomor Induk Kependudukan (16 digit)" />
      </div>
      <div class="mb-3">
        <label class="form-label">Nama Lengkap:</label>
        <input type="text" name="name[]" value="${name}" required class="form-control" placeholder="Nama sesuai KTP/pengunjung" />
      </div>
      <div class="mb-3">
        <label class="form-label">No. Telepon (boleh kosong):</label>
        <input type="text" name="phone[]" value="${phone}" class="form-control" placeholder="Contoh: 081234567890" />
      </div>
      <button type="button" class="btn btn-link text-danger p-0" onclick="this.parentElement.remove()">Hapus Pengunjung</button>
    `;
    wrapper.appendChild(div);
  }

  // Auto-tambah satu pengunjung saat pertama kali
  window.addEventListener('DOMContentLoaded', () => {
    @if(old('nik'))
      @foreach(old('nik') as $i => $nik)
        addPengunjung(
          @json(old('nik')[$i] ?? ''),
          @json(old('name')[$i] ?? ''),
          @json(old('phone')[$i] ?? '')
        );
      @endforeach
    @else
      addPengunjung();
    @endif
  });
</script>
@endsection
