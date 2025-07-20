<!DOCTYPE html>
<html>
<head>
    <title>Pembelian Tiket Konser</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .pengunjung { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .pengunjung input { display: block; margin-bottom: 5px; width: 100%; padding: 6px; }
        .remove-btn { background: red; color: white; border: none; padding: 4px 8px; cursor: pointer; margin-top: 5px; }
        .add-btn { background: green; color: white; padding: 8px 12px; cursor: pointer; border: none; }
        .submit-btn { background: blue; color: white; padding: 10px 15px; cursor: pointer; margin-top: 20px; }
        .error-msg { color: red; margin-bottom: 10px; }
    </style>
    <script>
        function addPengunjung(nik = '', name = '', phone = '') {
            const wrapper = document.getElementById('pengunjung-list');

            const div = document.createElement('div');
            div.className = 'pengunjung';
            div.innerHTML = `
                <label>NIK:</label>
                <input type="text" name="nik[]" value="${nik}" required>

                <label>Nama Lengkap:</label>
                <input type="text" name="name[]" value="${name}" required>

                <label>No. Telepon (boleh kosong):</label>
                <input type="text" name="phone[]" value="${phone}">

                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">Hapus Pengunjung</button>
            `;
            wrapper.appendChild(div);
        }
    </script>
</head>
<body>

    <h2>Pembelian Tiket Konser</h2>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="error-msg">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ticket.store') }}" method="POST">
        @csrf

        <label>Email Pembeli:</label>
        <input type="email" name="email" required value="{{ old('email') }}"><br><br>

        <div id="pengunjung-list">
            @if(old('nik'))
                @foreach(old('nik') as $i => $nik)
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            addPengunjung(
                                @json(old('nik')[$i] ?? ''),
                                @json(old('name')[$i] ?? ''),
                                @json(old('phone')[$i] ?? '')
                            );
                        });
                    </script>
                @endforeach
            @else
                <div class="pengunjung">
                    <label>NIK:</label>
                    <input type="text" name="nik[]" required>

                    <label>Nama Lengkap:</label>
                    <input type="text" name="name[]" required>

                    <label>No. Telepon (boleh kosong):</label>
                    <input type="text" name="phone[]">

                    <button type="button" class="remove-btn" onclick="this.parentElement.remove()">Hapus Pengunjung</button>
                </div>
            @endif
        </div>

        <button type="button" class="add-btn" onclick="addPengunjung()">+ Tambah Pengunjung</button><br><br>

        <button type="submit" class="submit-btn">Lanjut ke Pembayaran</button>
    </form>

</body>
</html>
