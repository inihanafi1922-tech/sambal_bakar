@php($editing = isset($product))
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="form-card card">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field span-all">
            <label for="name">Nama Menu</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Contoh: Ayam Bakar Sambal Bawang" required>
        </div>

        <div class="field">
            <label for="price">Harga (Rp)</label>
            <input type="number" id="price" name="price" min="0" step="500" value="{{ old('price', $product->price ?? '') }}" placeholder="28000" required>
        </div>

        <div class="field">
            <label for="category">Kategori</label>
            <input type="text" id="category" name="category" list="categoryOptions" value="{{ old('category', $product->category ?? '') }}" placeholder="Makanan / Minuman" required>
            <datalist id="categoryOptions">
                @foreach (($categories ?? collect()) as $category)
                    <option value="{{ $category }}"></option>
                @endforeach
            </datalist>
        </div>

        <div class="field">
            <label for="stock">Jumlah / Stok (opsional)</label>
            <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $product->stock ?? '') }}" placeholder="Kosongkan jika tidak diatur">
            <span class="hint">Boleh tidak diisi.</span>
        </div>

        <div class="field">
            <label for="image">Foto Makanan (opsional)</label>
            <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp">
            <span class="hint">Format JPG, PNG, atau WEBP. Maks 2MB.</span>
            @if ($editing && $product->image)
                <div style="margin-top:10px;">
                    <img src="{{ '/'.$product->image }}" alt="{{ $product->name }}" style="width:120px;height:90px;object-fit:cover;border-radius:12px;border:1px solid var(--line);">
                    <span class="hint">Biarkan kosong jika tidak ingin mengganti foto.</span>
                </div>
            @endif
        </div>

        <div class="field span-all">
            <label class="field-check">
                <input type="checkbox" name="has_spicy_level" value="1" @checked(old('has_spicy_level', $product->has_spicy_level ?? false))>
                Menu memiliki pilihan level pedas
            </label>
        </div>

        <div class="field span-all btn-row">
            <button type="submit" class="btn">{{ $submitLabel }}</button>
            <a href="{{ route('admin.products.index') }}" class="btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
        </div>
    </div>
</form>
