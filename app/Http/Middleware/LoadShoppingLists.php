<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class LoadShoppingLists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user())
        {
            if( !Cart::instance('default')->count() )
            {
                Cart::instance('default')->restore(Auth::User()->email);
            }
            if( !Cart::instance('wishlist')->count() )
            {
                Cart::instance('wishlist')->restore(Auth::User()->email);
            }
        }
        
        return $next($request);
    }
}
