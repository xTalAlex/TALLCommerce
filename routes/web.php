<?php

use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $carousel_products = App\Models\Product::inRandomOrder()->take(5)->get();
    $featured_products = App\Models\Product::featured()->inRandomOrder()->take(1)->get();
    return view('welcome', compact('carousel_products','featured_products') );
})->name('home');

Route::get('/shop', [App\Http\Controllers\ProductController::class , 'index'] )->name('product.index');
Route::get('/shop/{product}', App\Http\Livewire\Product\Show::class )->name('product.show');

Route::get('/cart', App\Http\Livewire\Cart\Index::class )->name('cart.index');

Route::get('/wishlist', App\Http\Livewire\Wishlist\Index::class )->name('wishlist.index');

Route::post('/checkout/stripe', [App\Http\Controllers\StripeController::class , 'checkout'] )->name('stripe.checkout');
Route::get('/checkout/stripe/success', [App\Http\Controllers\StripeController::class , 'success'] )->name('stripe.success');
Route::get('/checkout/stripe/cancel', [App\Http\Controllers\StripeController::class , 'cancel'] )->name('stripe.cancel');

Route::get('/order/create', App\Http\Livewire\Order\Create::class )->name('order.create');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class , 'index'] )->name('order.index');
});

Route::middleware([
    //admin
])
->prefix('admin')
->group(function () {
    Route::get('login', fn() => redirect('login') );
    // Route::get('storage/livewire-temp/{filename}', function ($filename)
    // {
    //     return Image::make(storage_path('app/orchid-temp/'.$filename))->response();
    // });
});

Route::stripeWebhooks('stripe-webhook');

