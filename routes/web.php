<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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
Route::get('/it', function(){ 
    Session::put('locale', 'it');
    return redirect()->back();
} );
Route::get('/en', function(){ 
    Session::put('locale', 'en');
    return redirect()->back();
} );

Route::get('/', [App\Http\Controllers\HomeController::class , 'index'] )->name('home');
Route::get('/about-us', [App\Http\Controllers\HomeController::class , 'aboutUs'] )->name('about-us');
Route::get('/delivery', [App\Http\Controllers\HomeController::class , 'delivery'] )->name('delivery');
Route::get('/info', [App\Http\Controllers\HomeController::class , 'info'] )->name('info');
Route::get('/contact-us', [App\Http\Controllers\HomeController::class , 'contactUs'] )->name('contact-us');

Route::get('/shop', App\Http\Livewire\Product\Index::class )->name('product.index');
Route::get('/shop/{product:slug}', App\Http\Livewire\Product\Show::class )->name('product.show');

Route::get('/checkout/response/stripe', [App\Http\Controllers\StripeController::class , 'handleCheckoutResponse'] )->name('stripe.handle.checkout.response');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    //'verified'
])->group(function () {

    Route::get('/cart', App\Http\Livewire\Cart\Index::class )->name('cart.index');

    Route::get('/wishlist', App\Http\Livewire\Wishlist\Index::class )->name('wishlist.index');

    Route::get('/shop/{product:slug}/login', fn(App\Models\Product $product) => redirect()->route('product.show', $product) )->name('product.login');
    
    Route::get('/order/create', App\Http\Livewire\Order\Create::class )->name('order.create');
    Route::get('/order/{order:number}', [App\Http\Controllers\OrderController::class , 'show'] )->name('order.show');
    Route::get('/order/{order:number}/update', App\Http\Livewire\Order\Update::class )->name('order.update');

    Route::get('/order/{order:number}/invoice', [App\Http\Controllers\InvoiceController::class , 'show'] )->name('invoice.show');

    Route::get('/orders', [App\Http\Controllers\OrderController::class , 'index'] )->name('order.index');
    Route::get('/order/create/login', fn() => redirect()->route('order.create') )->name('order.login');
    Route::get('/order/{order}/reorder', [App\Http\Controllers\OrderController::class , 'reorder'] )->name('order.reorder');

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
        Route::get('admin', function() {
            return (new App\Notifications\OrderCompleted(App\Models\Order::latest()->first()))->toMail('admin@admin.com');
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