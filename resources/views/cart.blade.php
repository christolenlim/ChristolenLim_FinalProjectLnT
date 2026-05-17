@extends('layouts.app')

@section('content')
<h2>Keranjang Belanja</h2>

@if(empty($cart))
    <div class="alert alert-warning">Keranjang kamu kosong. <a href="{{ route('katalog') }}">Belanja sekarang</a></div>
@else
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($cart as $item)
        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
            <td>{{ $item['quantity'] }}</td>
            <td>Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total:</th>
            <th>Rp. {{ number_format($total, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<hr>
<h4>Data Pengiriman</h4>
<form action="{{ route('checkout') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Alamat Pengiriman (min 10, max 100 huruf)</label>
        <textarea name="shipping_address" class="form-control" minlength="10" maxlength="100" required></textarea>
        @error('shipping_address') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label>Kode Pos (5 digit angka)</label>
        <input type="text" name="postal_code" class="form-control" maxlength="5" pattern="\d{5}" required>
        @error('postal_code') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <a href="{{ route('katalog') }}" class="btn btn-secondary">Lanjut Belanja</a>
    <button type="submit" class="btn btn-success">Buat Faktur</button>
</form>
@endif
@endsection