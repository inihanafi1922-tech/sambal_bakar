@extends('layouts.staff')

@section('title', 'Ubah Menu - Sambal Bakar')
@section('subtitle', 'Ubah Menu')

@section('content')
    <div class="page-head">
        <h2>Ubah Menu</h2>
        <p>Perbarui nama, harga, kategori, foto, atau stok untuk {{ $product->name }}.</p>
    </div>

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    @include('admin.products._form', [
        'action' => route('admin.products.update', $product),
        'submitLabel' => 'Simpan Perubahan',
    ])

    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus menu {{ $product->name }}?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn danger">Hapus Menu Ini</button>
    </form>
@endsection
