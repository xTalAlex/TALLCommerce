<?php

namespace App\Actions;

use Closure;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class PrepareShoppingListsSession
{

    public function __invoke(Request $request, Closure $next)
    {
        if (Cart::instance('default')->count())
        {
            try {
                Cart::instance('default')->erase(Auth::user()->email); 
            }catch (Throwable $e) {
                //Log::warning('Attempt to delete cart on login failed');
            }
            Cart::instance('default')->store(Auth::user()->email);
        }

        if (Cart::instance('wishlist')->count())
        {
            $products = Cart::instance('wishlist')->content()->map( fn($item) => $item->model );
            Cart::instance('wishlist')->restore(Auth::user()->email);
            foreach($products as $product){
                $duplicate = Cart::instance('wishlist')->search(function ($item, $row) use ($product) {
                    return $item->id === $product->id;
                });
                if (!$duplicate) {
                    Cart::instance('wishlist')->add($product, 1);
                }
            }
            Cart::instance('wishlist')->store(Auth::user()->email);
        }

        return $next($request);
    }
}