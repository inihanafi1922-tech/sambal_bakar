@extends('layouts.staff')

@section('title', 'Pesanan & Pembayaran - Sambal Bakar')
@section('subtitle', 'Konfirmasi Pembayaran')

@section('content')
    <style>
        .scan-form { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .scan-form input {
            flex: 1 1 260px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 11px 13px;
            font-size: 14px;
            font-weight: 700;
            background: #fdfaf6;
            color: var(--ink);
            outline: none;
            text-transform: uppercase;
        }
        .scan-form input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }
        tbody tr.row-found td { background: #fff3e0; }
        tbody tr.row-found:hover td { background: #fff3e0; }
        .code-mini { font-size: 11px; letter-spacing: 1px; }
    </style>

    <div class="page-head">
        <h2>Pesanan &amp; Konfirmasi Pembayaran</h2>
        <p>Daftar pesanan terbaru, termasuk pesanan dari HP pelanggan. Konfirmasi pembayaran dilakukan di halaman ini.</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="card form-card">
        <div class="section-title" style="margin-bottom:12px;">
            <h3>Cari / Scan Barcode Pesanan</h3>
        </div>
        <form method="GET" action="{{ route('kasir.payments') }}" class="scan-form">
            <input type="text" name="cari" value="{{ $search }}" placeholder="Scan barcode struk atau ketik kode lacak / no. order" autocomplete="off" autofocus>
            <button type="submit" class="btn">Cari</button>
            @if ($search !== '')
                <a href="{{ route('kasir.payments') }}" class="btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;">Reset</a>
            @endif
        </form>
    </div>

    @if ($search !== '' && $recentOrders->isEmpty())
        <div class="alert error">Order dengan kode "{{ $search }}" tidak ditemukan.</div>
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
        <div style="padding: 18px 20px 6px;">
            <div class="section-title">
                <h3>Daftar Pesanan</h3>
                <a href="{{ route('kasir.index') }}">Input Pesanan Baru</a>
            </div>
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
                        <tr class="{{ $search !== '' ? 'row-found' : '' }}">
                            <td>
                                <strong>#{{ $order->id }}</strong><br>
                                <span class="muted code-mini">{{ $order->tracking_code }}</span>
                            </td>
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
                                    <a href="{{ route('kasir.order.receipt', $order) }}" target="_blank" rel="noopener" class="btn ghost small" style="text-decoration:none;display:inline-flex;align-items:center;">Struk</a>
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
                            <td colspan="7" class="empty-row">{{ $search !== '' ? 'Order tidak ditemukan.' : 'Belum ada pesanan. Pesanan baru akan muncul di sini.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
