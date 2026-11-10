@extends('layouts.staff')

@section('title', 'Ubah Password - Sambal Bakar')
@section('subtitle', 'Ubah Password')

@section('content')
    <div class="page-head">
        <h2>Ubah Password</h2>
        <p>Ganti password akun <strong>{{ auth()->user()->email }}</strong>. Minimal 8 karakter.</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="form-card card" style="max-width:520px;">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="field span-all">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" placeholder="Masukkan password lama" required autofocus>
            </div>

            <div class="field span-all">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="field span-all">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
            </div>
        </div>

        <div class="field span-all btn-row" style="margin-top:14px;">
            <button type="submit" class="btn">Simpan Password</button>
        </div>
    </form>
@endsection
