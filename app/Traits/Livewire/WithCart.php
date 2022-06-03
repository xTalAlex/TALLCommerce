<?php

namespace App\Traits\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;

trait WithCart
{
    public Product $product;
    public $cartInstance = "default";
    public $wishlistInstance = "wishlist";

    /** 
     * 
     *      ADD
     * 
     * **/

    public function addToCart()
    {
        Cart::add($this->product,1);
        $this->notifyCart();
        $this->notifyBanner('Product added to cart');
    }

    public function addToWishlist()
    {
        $product = $this->product; 

        $duplicates = Cart::instance($this->wishlistInstance)->search(function ($item, $row) use ($product) {
            return $item->id === $product->id;
        });

        if (!$duplicates->count()) {
            Cart::instance($this->wishlistInstance)->add($this->product, 1);
            $this->notifyWishlist();
            $this->notifyBanner('Product added to wishlist');
        }
    }


    /** 
     * 
     *      UPDATE
     * 
     * **/

    public function updateCartProductQty($rowId, $qty)
    {
        Cart::instance($this->cartInstance)->update($rowId, $qty);
        $this->notifyCart();
    }


    /** 
     * 
     *      MOVE
     * 
     * **/

    public function moveToCart(Product $product)
    {
        Cart::instance($this->cartInstance)->add($product,1);
        $items = Cart::instance($this->wishlistInstance)->search(function ($item, $rowId) use ($product) {
            return $item->id === $product->id;
        });
        if ($items) {
            Cart::instance($this->wishlistInstance)->remove($items->first()->rowId);
        }

        $this->notifyCart();
        $this->notifyWishlist();
        $this->notifyBanner('Product added to cart');
    }

    public function moveToWishlist(Product $product)
    {
        $duplicates = Cart::instance($this->wishlistInstance)->search(function ($item, $row) use ($product) {
            return $item->id === $product->id;
        });
        if (!$duplicates->count()) {
            Cart::instance($this->wishlistInstance)->add($product, 1);
        }

        $items = Cart::instance($this->cartInstance)->search(function ($item, $rowId) use ($product) {
            return $item->id === $product->id;
        });
        if ($items) {
            Cart::instance($this->cartInstance)->remove($items->first()->rowId);
        }

        $this->notifyCart();
        $this->notifyWishlist();
        $this->notifyBanner('Product added to wishlist');
    }


    /** 
     * 
     *      REMOVE
     * 
     * **/

    public function removeFromCart(Product $product)
    {
        $items = Cart::instance($this->cartInstance)->search(function ($item, $rowId) use ($product) {
            return $item->id === $product->id;
        });
        foreach( $items as $item)
            Cart::instance($this->cartInstance)->remove($item->rowId);
        $this->notifyCart();
    }

    public function removeFromWishlist(Product $product)
    {
        $items = Cart::instance($this->wishlistInstance)->search(function ($item, $rowId) use ($product) {
            return $item->id === $product->id;
        });
        foreach( $items as $item)
            Cart::instance($this->wishlistInstance)->remove($item->rowId);
        $this->notifyWishlist();
    }


    /** 
     * 
     *      CLEAR
     * 
     * **/

    public function deleteCart()
    {
        Cart::instance($this->cartInstance)->destroy();
        $this->notifyCart();   
    }

    public function deleteWishlist()
    {
        Cart::instance($this->wishlistInstance)->destroy();
        $this->notifyWishlist();
    }


    /** 
     * 
     *      NOTIFICATIONS
     * 
     * **/

    public function notifyCart()
    {
        $this->dispatchBrowserEvent(
            'cart-updated', [
                'count' => Cart::instance($this->cartInstance)->count(),
            ]);
    }

    public function notifyWishlist()
    {
        $this->dispatchBrowserEvent(
            'wishlist-updated', [
                'count' => Cart::instance($this->wishlistInstance)->count(),
            ]);
    }

    public function notifyBanner($message, $style = 'success')
    {
        $this->dispatchBrowserEvent('banner-message', [
            'message' => $message,
            'style' => $style,
        ]);
    }


}