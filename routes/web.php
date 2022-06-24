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

Route::get('/order/create', App\Http\Livewire\Order\Create::class )->name('order.create');
Route::get('/order/{order}', [App\Http\Controllers\OrderController::class , 'show'] )->name('order.show');
Route::get('/order/update/{order}', App\Http\Livewire\Order\Update::class )->name('order.update');

Route::get('/checkout/response/stripe', [App\Http\Controllers\StripeController::class , 'handleCheckoutResponse'] )->name('stripe.handle.checkout.response');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class , 'index'] )->name('order.index');
    Route::get('/order/create/login', fn() => redirect()->route('order.create') )->name('order.login');

    Route::post('review/{product}/store', [App\Http\Controllers\ReviewController::class , 'store'] )->name('review.store');
    Route::post('review/{review}/destroy', [App\Http\Controllers\ReviewController::class , 'destroy'] )->name('review.destroy');
});

Route::middleware([
    'admin'
])
->prefix('admin')
->group(function () {
    Route::get('login', fn() => redirect('login') )->name('filament.auth.login');

    Route::prefix('mail')->group( function() {
        Route::get('order/placed', function() {
            return "Email";
        });
    });

});

Route::stripeWebhooks('stripe-webhook');