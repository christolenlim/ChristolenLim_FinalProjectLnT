@extends('layouts.app')

@section('content')
<h2>Tambah Produk Baru</h2>
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Nama Produk (min 5, max 80 huruf)</label>
        <input type="text" name="name" class="form-control" minlength="5" maxlength="80" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Harga (angka saja, contoh: 50000)</label>
        <div class="input-group">
            <span class="input-group-text">Rp.</span>
            <input type="number" name="price" class="form-control" required>
        </div>
        @error('price') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Jumlah Stok</label>
        <input type="number" name="stock" class="form-control" required>
        @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Foto Produk</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
        @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan Produk</button>
</form>
@endsection