@extends('layouts.staff')

@section('title', 'Laporan Keuangan - Sambal Bakar')
@section('subtitle', 'Laporan Keuangan')

@section('content')
    <div class="page-head">
        <h2>Laporan Keuangan</h2>
        <p>Pemasukan dan pengeluaran uang secara real time, lengkap dengan grafik bulanan dan tahunan.</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="stat-grid">
        <div class="stat accent">
            <span>Pemasukan Hari Ini</span>
            <strong>Rp {{ number_format($summary['today_income'], 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Pengeluaran Hari Ini</span>
            <strong>Rp {{ number_format($summary['today_expense'], 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Saldo Hari Ini</span>
            <strong>Rp {{ number_format($summary['today_balance'], 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Pemasukan {{ $selectedYear }}</span>
            <strong>Rp {{ number_format($summary['year_income'], 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Pengeluaran {{ $selectedYear }}</span>
            <strong>Rp {{ number_format($summary['year_expense'], 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Saldo {{ $selectedYear }}</span>
            <strong>Rp {{ number_format($summary['year_balance'], 0, ',', '.') }}</strong>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports') }}" class="card filter-form">
        <div>
            <label for="year">Tahun</label>
            <select id="year" name="year">
                @foreach ($yearOptions as $year)
                    <option value="{{ $year }}" @selected($year === $selectedYear)>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn">Tampilkan</button>
        <button type="button" class="btn dark" style="margin-left:auto;" onclick="window.print()">Cetak Laporan</button>
    </form>

    <div class="grid-2" style="margin-bottom:22px;">
        <div class="card chart-card">
            <h3>Grafik Bulanan {{ $selectedYear }}</h3>
            <p>Pemasukan (garis) dan pengeluaran (batang) per bulan. Lihat apakah penghasilan menaik atau menurun.</p>
            <div class="chart-box"><canvas id="monthlyChart"></canvas></div>
        </div>
        <div class="card chart-card">
            <h3>Grafik Tahunan</h3>
            <p>Perbandingan pemasukan dan pengeluaran 5 tahun terakhir.</p>
            <div class="chart-box"><canvas id="yearlyChart"></canvas></div>
        </div>
    </div>

    <div class="card form-card">
        <div class="section-title"><h3>Catat Pemasukan / Pengeluaran</h3></div>
        <form method="POST" action="{{ route('admin.transactions.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="type">Jenis</label>
                    <select id="type" name="type" required>
                        <option value="income" @selected(old('type') === 'income')>Pemasukan</option>
                        <option value="expense" @selected(old('type') === 'expense')>Pengeluaran</option>
                    </select>
                </div>
                <div class="field">
                    <label for="amount">Jumlah (Rp)</label>
                    <input type="number" id="amount" name="amount" min="1" step="500" value="{{ old('amount') }}" placeholder="50000" required>
                </div>
                <div class="field">
                    <label for="category">Kategori (opsional)</label>
                    <input type="text" id="category" name="category" list="financeCategories" value="{{ old('category') }}" placeholder="Bahan Baku / Operasional">
                    <datalist id="financeCategories">
                        <option value="Penjualan"></option>
                        <option value="Bahan Baku"></option>
                        <option value="Gaji Staf"></option>
                        <option value="Operasional"></option>
                        <option value="Lainnya"></option>
                    </datalist>
                </div>
                <div class="field">
                    <label for="transaction_date">Tanggal</label>
                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required>
                </div>
                <div class="field span-all">
                    <label for="description">Keterangan (opsional)</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" placeholder="Contoh: Belanja ayam dan bumbu">
                </div>
                <div class="field span-all">
                    <button type="submit" class="btn">Simpan Transaksi</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div style="padding: 18px 20px 6px;">
            <div class="section-title">
                <h3>Transaksi {{ $selectedYear }}</h3>
                <span class="muted" style="font-size:12px;">{{ $transactions->count() }} transaksi terakhir</span>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="muted">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $transaction->type }}">
                                    {{ $transaction->isIncome() ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="muted">{{ $transaction->category ?: '—' }}</td>
                            <td class="muted">{{ $transaction->description ?: '—' }}</td>
                            <td class="money {{ $transaction->type }}">
                                {{ $transaction->isIncome() ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.transactions.destroy', ['transaction' => $transaction, 'year' => $selectedYear]) }}" class="inline-form" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger small">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-row">Belum ada transaksi pada tahun ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            .topbar, .filter-form, .page-head, .form-card, .chart-card .btn { display: none !important; }
            body { background: #fff; }
            .page { margin: 0; width: 100%; }
            .card, .stat { box-shadow: none; border-color: #ccc; }
        }
    </style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const monthly = @json($monthly);
    const yearly = @json($yearly);
    const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

    const moneyTooltip = {
        callbacks: {
            label: (context) => context.dataset.label + ': ' + rupiah(context.parsed.y),
        },
    };

    new Chart(document.getElementById('monthlyChart'), {
        data: {
            labels: monthly.labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Pemasukan',
                    data: monthly.income,
                    borderColor: '#1e7a3d',
                    backgroundColor: 'rgba(30, 122, 61, .12)',
                    fill: true,
                    tension: .35,
                    pointRadius: 3,
                    borderWidth: 2,
                },
                {
                    type: 'bar',
                    label: 'Pengeluaran',
                    data: monthly.expense,
                    backgroundColor: 'rgba(214, 40, 40, .55)',
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { callback: (value) => rupiah(value) } } },
            plugins: { tooltip: moneyTooltip, legend: { position: 'bottom' } },
        },
    });

    new Chart(document.getElementById('yearlyChart'), {
        type: 'bar',
        data: {
            labels: yearly.labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: yearly.income,
                    backgroundColor: 'rgba(30, 122, 61, .75)',
                    borderRadius: 6,
                },
                {
                    label: 'Pengeluaran',
                    data: yearly.expense,
                    backgroundColor: 'rgba(214, 40, 40, .65)',
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { callback: (value) => rupiah(value) } } },
            plugins: { tooltip: moneyTooltip, legend: { position: 'bottom' } },
        },
    });
</script>
@endpush
