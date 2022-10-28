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
    $featured_categories = App\Models\Category::featured()->take(5)->get();
    $featured_products = App\Models\Product::featured()->where('quantity','>',0)->inRandomOrder()->take(1)->get();
    $brands = App\Models\Brand::whereHas('media', fn($query)=>
            $query->whereCollectionName('logo')
        )->get();
    if($brands->count())
        $brands = $brands->mapWithKeys(fn($brand,$key) => [
            $key => [
                'logo' => $brand->logo_gray,
                'url' => route('product.index', ['brand' => $brand->slug])
            ]
        ]);
    $collections = App\Models\Collection::featured()->inRandomOrder()->take(3)->get();
    if($collections->count())
        $collections = $collections->mapWithKeys(fn($collection,$key) => [
            $key => [
                'hero' => $collection->hero,
                'url' => route('product.index', ['collection' => $collection->slug]),
                'name' => $collection->name,
                'description' => $collection->description
            ]
        ]);
    return view('welcome', compact('featured_categories','featured_products','brands','collections') );
})->name('home');

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
    Route::post('review/{review}/destroy', [App\Http\Controllers\ReviewController::class , 'destroy'] )->name('review.destroy');
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