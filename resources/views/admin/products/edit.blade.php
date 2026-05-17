@extends('layouts.app')

@section('content')
<h2>Edit Produk</h2>
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-control" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" value="{{ $product->name }}" minlength="5" maxlength="80" required>
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <div class="input-group">
            <span class="input-group-text">Rp.</span>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>
    </div>

    <div class="mb-3">
        <label>Jumlah Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
    </div>

    <div class="mb-3">
        <label>Foto Produk (kosongkan jika tidak ingin ganti)</label>
        @if($product->photo)
            <br><img src="{{ asset('storage/' . $product->photo) }}" width="100" class="mb-2">
        @endif
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-warning">Update Produk</button>
</form>
@endsection