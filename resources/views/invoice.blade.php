@extends('layouts.app')

@section('content')
<div class="card p-4" id="invoice-area">
    <h2 class="text-center">FAKTUR PEMBELIAN</h2>
    <h4 class="text-center">PT Londo Bell</h4>
    <hr>

    <p><strong>Nomor Invoice:</strong> {{ $invoice->invoice_number }}</p>
    <p><strong>Nama Pembeli:</strong> {{ $invoice->user->name }}</p>
    <p><strong>Alamat Pengiriman:</strong> {{ $invoice->shipping_address }}</p>
    <p><strong>Kode Pos:</strong> {{ $invoice->postal_code }}</p>
    <p><strong>Tanggal:</strong> {{ $invoice->created_at->format('d-m-Y H:i') }}</p>

    <hr>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product->name }} x{{ $item->quantity }}</td>
                <td>{{ $item->product->category->name }}</td>
                <td>Rp. {{ number_format($item->product->price, 0, ',', '.') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total Harga:</th>
                <th>Rp. {{ number_format($invoice->total_price, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="mt-3">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Faktur</button>
    <a href="{{ route('katalog') }}" class="btn btn-secondary">Kembali ke Katalog</a>
</div>
@endsection