@extends('layouts.staff')

@section('title', 'Pesanan & Pembayaran - Sambal Bakar')
@section('subtitle', 'Konfirmasi Pembayaran')

@section('content')
    <div class="page-head">
        <h2>Pesanan &amp; Konfirmasi Pembayaran</h2>
        <p>Daftar pesanan terbaru. Konfirmasi pembayaran tunai dilakukan di halaman ini.</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="stat-grid">
        <div class="stat accent">
            <span>Menunggu Konfirmasi</span>
            <strong>{{ $pendingPayments->count() }}</strong>
        </div>
        <div class="stat">
            <span>Pesanan Terbaru</span>
            <strong>{{ $recentOrders->count() }}</strong>
        </div>
    </div>

    <div class="card">
        <div class="section-title" style="padding:20px 20px 0;">
            <h3>Daftar Pesanan</h3>
            <a href="{{ route('kasir.index') }}">Input Pesanan Baru</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Meja / Pelanggan</th>
                        <th>Waktu</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->table_number }}</td>
                            <td class="muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->paymentMethodLabel() }}</td>
                            <td class="money">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $order->isPaid() ? 'served' : 'pending' }}">
                                    {{ $order->isPaid() ? 'Lunas' : 'Menunggu Konfirmasi' }}
                                </span>
                            </td>
                            <td>
                                @if ($order->isPaid())
                                    <a href="{{ route('kasir.order.receipt', $order) }}" target="_blank" rel="noopener" class="btn ghost small">Struk</a>
                                @else
                                    <form method="POST" action="{{ route('kasir.payment.confirm', $order) }}" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn small">Konfirmasi Lunas</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">Belum ada pesanan. Pesanan baru akan muncul di sini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
