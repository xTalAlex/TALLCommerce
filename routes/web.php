<?php

use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

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
Route::get('/', fn () => view('welcome') );
Route::get('/home', [App\Http\Controllers\HomeController::class , 'index'] )->name('home');

Route::get('/shop', [App\Http\Controllers\ProductController::class , 'index'] )->name('product.index');
Route::get('/shop/{product:slug}', App\Http\Livewire\Product\Show::class )->name('product.show');

Route::get('/cart', App\Http\Livewire\Cart\Index::class )->name('cart.index');

Route::get('/wishlist', App\Http\Livewire\Wishlist\Index::class )->name('wishlist.index');

Route::get('/order/create', App\Http\Livewire\Order\Create::class )->name('order.create');
Route::get('/order/{order:number}', [App\Http\Controllers\OrderController::class , 'show'] )->name('order.show');
Route::get('/order/{order:number}/update', App\Http\Livewire\Order\Update::class )->name('order.update');

Route::get('/order/{order:number}/invoice', [App\Http\Controllers\InvoiceController::class , 'show'] )->name('invoice.show');

Route::get('/checkout/response/stripe', [App\Http\Controllers\StripeController::class , 'handleCheckoutResponse'] )->name('stripe.handle.checkout.response');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class , 'index'] )->name('order.index');
    Route::get('/order/create/login', fn() => redirect()->route('order.create') )->name('order.login');

    Route::post('review/{product}/store', [App\Http\Controllers\ReviewController::class , 'store'] )->name('review.store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
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

Route::get('/auth/google/redirect', [App\Http\Controllers\SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\SocialiteController::class, 'handleGoogleCallBack']);

Route::stripeWebhooks('stripe-webhook');

Route::get('sitemap_index.xml', fn() => 
    response(
        Storage::disk(config('media-library.disk_name'))->get('sitemap_index.xml'), 
        200,
        ['Content-Type' => 'application/xml']
    )
);
Route::get('sitemap/{filename}', fn($filename) => 
    response(
        Storage::disk(config('media-library.disk_name'))->get('sitemap/'.$filename), 
        200,
        ['Content-Type' => 'application/xml']
    )
);