<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Admin: Lihat semua produk
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    // Admin: Form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Admin: Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|min:5|max:80',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer',
            'stock'       => 'required|integer',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Admin: Form edit produk
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Admin: Update produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|min:5|max:80',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer',
            'stock'       => 'required|integer',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diupdate!');
    }

    // Admin: Hapus produk
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    // User: Halaman katalog
    public function katalog()
    {
        $products = Product::with('category')->get();
        return view('katalog', compact('products'));
    }

    // User: Tambah ke keranjang (session)
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock <= 0) {
            return redirect()->back()
                             ->with('error', 'Barang sudah habis, silakan tunggu hingga barang di-restock ulang.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,
                'photo'    => $product->photo,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang!');
    }

    // User: Lihat keranjang
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // User: Checkout - simpan faktur
    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10|max:100',
            'postal_code'      => 'required|digits:5',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('katalog')
                             ->with('error', 'Keranjang kamu kosong!');
        }

        // Generate nomor invoice otomatis
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        // Hitung total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Simpan invoice
        $invoice = Invoice::create([
            'invoice_number'   => $invoiceNumber,
            'user_id'          => auth()->id(),
            'shipping_address' => $request->shipping_address,
            'postal_code'      => $request->postal_code,
            'total_price'      => $total,
        ]);

        // Simpan item invoice
        foreach ($cart as $productId => $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'subtotal'   => $item['price'] * $item['quantity'],
            ]);

            // Kurangi stok
            $product = Product::find($productId);
            $product->stock -= $item['quantity'];
            $product->save();
        }

        // Kosongkan keranjang
        session()->forget('cart');

        return redirect()->route('invoice.show', $invoice->id)
                         ->with('success', 'Faktur berhasil dibuat!');
    }

    // User: Tampilkan faktur
    public function showInvoice($id)
    {
        $invoice = Invoice::with('items.product.category', 'user')->findOrFail($id);
        return view('invoice', compact('invoice'));
    }
}