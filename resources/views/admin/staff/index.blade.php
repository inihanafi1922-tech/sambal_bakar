@extends('layouts.staff')

@section('title', 'Staf Bertugas - Sambal Bakar')
@section('subtitle', 'Staf Bertugas')

@section('content')
    <div class="page-head">
        <h2>Staf Bertugas</h2>
        <p>Lihat siapa saja staf yang bertugas hari ini dan jadwal hari-hari berikutnya.</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="stat-grid">
        <div class="stat accent">
            <span>Staf Hari Ini</span>
            <strong>{{ $stats['today'] }}</strong>
        </div>
        <div class="stat">
            <span>Jadwal 30 Hari</span>
            <strong>{{ $stats['upcoming'] }}</strong>
        </div>
        <div class="stat">
            <span>Total Staf</span>
            <strong>{{ $stats['total_staff'] }}</strong>
        </div>
    </div>

    <div class="grid-2">
        <div class="card form-card">
            <div class="section-title">
                <h3>Bertugas Hari Ini</h3>
                <span class="muted" style="font-size:12px;">{{ now()->translatedFormat('d F Y') }}</span>
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
                <p class="empty-row">Belum ada staf yang dijadwalkan hari ini.</p>
            @endforelse

            <p class="hint" style="display:block;margin-top:14px;color:var(--muted);font-size:12px;">
                Admin hanya dapat melihat jadwal staf yang bertugas.
            </p>
        </div>

        <div class="card form-card">
            <div class="section-title"><h3>Jadwal Berikutnya</h3></div>

            @forelse ($upcoming as $date => $schedules)
                <div class="date-heading">
                    {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    <span>&middot; {{ $schedules->count() }} staf</span>
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
                        <span class="pill">{{ $schedule->shift }}</span>
                    </div>
                @endforeach
            @empty
                <p class="empty-row">Belum ada jadwal staf untuk 30 hari ke depan.</p>
            @endforelse
        </div>
    </div>
@endsection
