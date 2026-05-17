<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () { return view('welcome'); });

// Route Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('admin/products', ProductController::class)
         ->names('admin.products');
});

// Route User
Route::middleware(['auth'])->group(function () {
    Route::get('/katalog', [ProductController::class, 'katalog'])->name('katalog');
    Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
    Route::post('/checkout', [ProductController::class, 'checkout'])->name('checkout');
    Route::get('/invoice/{id}', [ProductController::class, 'showInvoice'])->name('invoice.show');
});

require __DIR__.'/auth.php';