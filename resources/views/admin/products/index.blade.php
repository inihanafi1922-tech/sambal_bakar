@extends('layouts.staff')

@section('title', 'Pengaturan Menu - Sambal Bakar')
@section('subtitle', 'Pengaturan Menu')

@section('content')
    <div class="page-head">
        <h2>Pengaturan Menu</h2>
        <p>Edit menu, atur harga, tambahkan foto makanan, dan atur jumlah stok (stok tidak wajib diisi).</p>
    </div>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    @include('admin.products._form', [
        'action' => route('admin.products.store'),
        'submitLabel' => 'Tambah Menu',
    ])

    <div class="card">
        <div style="padding: 18px 20px 6px;">
            <div class="section-title">
                <h3>Daftar Menu</h3>
                <span class="muted" style="font-size:12px;">{{ $products->count() }} menu</span>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Pedas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img src="{{ '/'.$product->image }}" alt="{{ $product->name }}" class="thumb">
                                @else
                                    <span class="thumb">{{ strtoupper(substr($product->category, 0, 1)) }}</span>
                                @endif
                            </td>
                            <td style="font-weight:700;">{{ $product->name }}</td>
                            <td class="muted">{{ $product->category }}</td>
                            <td class="money">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if (is_null($product->stock))
                                    <span class="muted">&mdash;</span>
                                @else
                                    {{ $product->stock }}
                                @endif
                            </td>
                            <td>
                                @if ($product->has_spicy_level)
                                    <span class="badge cooking">Ya</span>
                                @else
                                    <span class="muted">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn ghost small" style="text-decoration:none;display:inline-flex;align-items:center;">Ubah</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-form" onsubmit="return confirm('Hapus menu {{ $product->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn danger small">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">Belum ada menu. Tambahkan menu pertama di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
