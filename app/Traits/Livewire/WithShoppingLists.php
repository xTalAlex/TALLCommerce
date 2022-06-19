<?php

namespace App\Traits\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

trait WithShoppingLists
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
        if ($this->product->quantity)
        {
            Cart::instance($this->cartInstance)->add($this->product, 1);
            $this->persist($this->cartInstance);
            $this->notifyCart();
            $this->notifyBanner(__('shopping_cart.added.cart'));
        }
        else
        {
            $this->notifyBanner(__('shopping_cart.out_of_stock'), 'danger');
        }
    }

    public function addToWishlist()
    {
        $product = $this->product; 

        $duplicates = Cart::instance($this->wishlistInstance)->search(function ($item, $row) use ($product) {
            return $item->id === $product->id;
        });

        if (!$duplicates->count()) {
            Cart::instance($this->wishlistInstance)->add($this->product, 1);
            $this->persist($this->wishlistInstance);
            $this->notifyWishlist();
            $this->notifyBanner(__('shopping_cart.added.wishlist'));
        }
    }


    /** 
     * 
     *      UPDATE
     * 
     * **/

    public function updateCartProductQty($rowId, $qty)
    {
        $newQty=Cart::instance($this->cartInstance)->get($rowId)->qty;
        if (Cart::instance($this->cartInstance)->get($rowId)->model->quantity >= $qty)
        {
            Cart::instance($this->cartInstance)->update($rowId, $qty);
            $newQty = $qty;
            $this->persist($this->cartInstance);
            $this->notifyCart();
        }
        else
        {
            $this->notifyBanner(__('shopping_cart.left_quantity',
                ['quantity' => Cart::instance($this->cartInstance)->get($rowId)->model->quantity]), 'danger');
        }

        return $newQty;
    }


    /** 
     * 
     *      MOVE
     * 
     * **/

    public function moveToCart(Product $product)
    {
        if ($product->quantity) {
            Cart::instance($this->cartInstance)->add($product, 1);
            $items = Cart::instance($this->wishlistInstance)->search(function ($item, $rowId) use ($product) {
                return $item->id === $product->id;
            });
            if ($items) {
                Cart::instance($this->wishlistInstance)->remove($items->first()->rowId);
            }

            $this->persist($this->cartInstance);
            $this->persist($this->wishlistInstance);
            $this->notifyCart();
            $this->notifyWishlist();
            $this->notifyBanner(__('shopping_cart.added.cart'));
        }
        else
        {
            $this->notifyBanner(__('shopping_cart.out_of_stock'), 'danger'); 
        }
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

        $this->persist($this->cartInstance);
        $this->persist($this->wishlistInstance);
        $this->notifyCart();
        $this->notifyWishlist();
        $this->notifyBanner(__('shopping_cart.added.wishlist'));
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
        $this->persist($this->cartInstance);
        $this->notifyCart();
    }

    public function removeFromWishlist(Product $product)
    {
        $items = Cart::instance($this->wishlistInstance)->search(function ($item, $rowId) use ($product) {
            return $item->id === $product->id;
        });
        foreach( $items as $item)
            Cart::instance($this->wishlistInstance)->remove($item->rowId);
        $this->persist($this->wishlistInstance);
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
        $this->persist($this->cartInstance);
        $this->notifyCart();   
    }

    public function deleteWishlist()
    {
        Cart::instance($this->wishlistInstance)->destroy();
        $this->persist($this->wishlistInstance);
        $this->notifyWishlist();
    }


    /** 
     * 
     *      NOTIFICATIONS
     * 
     * **/

    public function notifyCart()
    {
        $this->emit('updatedCart');
        $this->dispatchBrowserEvent(
            'cart-updated', [
                'count' => Cart::instance($this->cartInstance)->count(),
            ]);
    }

    public function notifyWishlist()
    {
        $this->emit('updatedWishlist');
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

    /** 
     * 
     *      PERSIST LIST TO DB
     * 
     * **/

    public function persist($instance)
    {
        if(Auth::check()) {
            Cart::instance($instance)->erase(Auth::user()->email);
            Cart::instance($instance)->store(Auth::user()->email);
            Cart::instance($instance)->restore(Auth::user()->email);
        }

    }


}