@extends('layouts.app')

@section('content')
<h2>Katalog Produk</h2>
<div class="row">
    @foreach($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($product->photo)
                <img src="{{ asset('storage/' . $product->photo) }}" class="card-img-top" style="height:200px; object-fit:cover;">
            @else
                <div class="bg-secondary text-white text-center p-5">Tidak ada foto</div>
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">
                    <strong>Kategori:</strong> {{ $product->category->name }}<br>
                    <strong>Harga:</strong> Rp. {{ number_format($product->price, 0, ',', '.') }}<br>
                    <strong>Stok:</strong> {{ $product->stock }}
                </p>

                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary w-100">Masukkan ke Faktur</button>
                    </form>
                @else
                    <button class="btn btn-secondary w-100" disabled>
                        Barang sudah habis, silakan tunggu hingga barang di-restock ulang
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection