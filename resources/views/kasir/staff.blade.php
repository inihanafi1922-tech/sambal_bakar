@extends('layouts.staff')

@section('title', 'Staf Kasir - Sambal Bakar')
@section('subtitle', 'Staf Kasir')

@section('content')
    <div class="page-head">
        <h2>Staf Kasir</h2>
        <p>Isi nama yang bertugas menjaga kasir. Jadwal hari ini tidak bisa dihapus, sedangkan jadwal berikutnya bisa dihapus jika ada penggantian penjaga.</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="card form-card">
        <div class="section-title"><h3>Isi Nama Penjaga Kasir</h3></div>
        <form method="POST" action="{{ route('kasir.staff.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="name">Nama Penjaga</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Siti Rahma" required>
                </div>
                <div class="field">
                    <label for="duty_date">Tanggal Bertugas</label>
                    <input type="date" id="duty_date" name="duty_date" value="{{ old('duty_date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                </div>
                <div class="field">
                    <label for="shift">Shift</label>
                    <select id="shift" name="shift" required>
                        @foreach (['Pagi', 'Siang', 'Malam'] as $shift)
                            <option value="{{ $shift }}" @selected(old('shift') === $shift)>{{ $shift }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field span-all">
                    <button type="submit" class="btn">Simpan Nama Penjaga</button>
                    <span class="hint">Data langsung masuk ke bagian Staf di admin.</span>
                </div>
            </div>
        </form>
    </div>

    <div class="grid-2">
        <div class="card form-card">
            <div class="section-title">
                <h3>Bertugas Hari Ini</h3>
                <span class="muted" style="font-size:12px;">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>

            @forelse ($todaySchedules as $schedule)
                <div class="staff-row">
                    <div>
                        <div class="staff-name">{{ $schedule->displayName() }}</div>
                        <div class="staff-meta">
                            {{ $schedule->displayRole() }}
                            @if ($schedule->note)
                                &middot; {{ $schedule->note }}
                            @endif
                        </div>
                    </div>
                    <span class="pill">{{ $schedule->shift }}</span>
                </div>
            @empty
                <p class="empty-row">Belum ada penjaga kasir hari ini. Silakan isi nama di atas.</p>
            @endforelse

            <p class="hint" style="display:block;margin-top:14px;color:var(--muted);font-size:12px;">
                Jadwal hari ini hanya bisa dilihat dan tidak dapat dihapus.
            </p>
        </div>

        <div class="card form-card">
            <div class="section-title"><h3>Jadwal Berikutnya</h3></div>

            @forelse ($upcoming as $date => $schedules)
                <div class="date-heading">
                    {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    <span>&middot; {{ $schedules->count() }} penjaga</span>
                </div>
                @foreach ($schedules as $schedule)
                    <div class="staff-row">
                        <div>
                            <div class="staff-name">{{ $schedule->displayName() }}</div>
                            <div class="staff-meta">
                                {{ $schedule->displayRole() }}
                                @if ($schedule->note)
                                    &middot; {{ $schedule->note }}
                                @endif
                            </div>
                        </div>
                        <div class="btn-row">
                            <span class="pill">{{ $schedule->shift }}</span>
                            <form method="POST" action="{{ route('kasir.staff.destroy', $schedule) }}" class="inline-form" onsubmit="return confirm('Hapus jadwal ini? Silakan isi nama penjaga pengganti setelahnya.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger small">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @empty
                <p class="empty-row">Belum ada jadwal berikutnya.</p>
            @endforelse
        </div>
    </div>
@endsection
