@extends('layouts.staff')

@section('title', 'Pengaturan Pembayaran - Sambal Bakar')
@section('subtitle', 'Pengaturan Pembayaran')

@section('content')
    <div class="page-head">
        <h2>Pengaturan Pembayaran</h2>
        <p>Unggah foto QRIS dan isi rekening toko. QRIS dan rekening ini akan tampil di halaman pesan pelanggan (HP).</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="grid-2">
        <form method="POST" action="{{ route('admin.payment-settings.update') }}" enctype="multipart/form-data" class="form-card card">
            @csrf

            <div class="section-title" style="margin-bottom:14px;">
                <h3>QRIS</h3>
            </div>

            <div class="form-grid">
                <div class="field span-all">
                    <label for="qris_image">Foto QRIS</label>
                    <input type="file" id="qris_image" name="qris_image" accept="image/png,image/jpeg,image/webp" onchange="previewQris(this)">
                    <span class="hint">Format JPG, PNG, atau WEBP. Maks 2MB. Mengunggah foto baru akan mengganti yang lama.</span>
                </div>

                @if ($setting?->qris_image)
                    <div class="field span-all">
                        <label>QRIS Saat Ini</label>
                        <div class="qris-preview">
                            <img src="{{ '/'.$setting->qris_image }}" alt="QRIS" id="qrisPreview">
                        </div>
                        <label class="field-check" style="margin-top:10px;">
                            <input type="checkbox" name="remove_qris" value="1">
                            Hapus foto QRIS saat ini
                        </label>
                    </div>
                @else
                    <div class="field span-all">
                        <label>QRIS Saat Ini</label>
                        <span class="hint">Belum ada foto QRIS. Pelanggan akan diminta membayar di kasir.</span>
                    </div>
                @endif
            </div>

            <div class="field span-all btn-row" style="margin-top:14px;">
                <button type="submit" class="btn">Simpan Pengaturan</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.payment-settings.update') }}" class="form-card card">
            @csrf

            <div class="section-title" style="margin-bottom:14px;">
                <h3>Rekening Transfer</h3>
            </div>

            <div class="form-grid">
                <div class="field span-all">
                    <label for="bank_name">Nama Bank / E-Wallet</label>
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $setting?->bank_name ?? '') }}" placeholder="Contoh: BCA / GoPay" maxlength="60">
                </div>

                <div class="field span-all">
                    <label for="account_number">Nomor Rekening / Nomor HP</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $setting?->account_number ?? '') }}" placeholder="Contoh: 1234567890" maxlength="40">
                </div>

                <div class="field span-all">
                    <label for="account_holder">Atas Nama</label>
                    <input type="text" id="account_holder" name="account_holder" value="{{ old('account_holder', $setting?->account_holder ?? '') }}" placeholder="Contoh: Sambal Bakar" maxlength="100">
                </div>
            </div>

            <div class="field span-all btn-row" style="margin-top:14px;">
                <button type="submit" class="btn">Simpan Pengaturan</button>
                <span class="hint">Kosongkan semua kolom jika ingin menghapus info rekening.</span>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewQris(input) {
            const preview = document.getElementById('qrisPreview');
            if (!preview || !input.files || !input.files[0]) return;

            preview.src = URL.createObjectURL(input.files[0]);
        }
    </script>
@endpush
