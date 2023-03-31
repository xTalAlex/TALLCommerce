<?php

namespace App\Traits\Livewire;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

trait WithShoppingLists
{
    public $product;
    public $cartInstance = "default";
    public $wishlistInstance = "wishlist";

    /** 
     * 
     *      ADD
     * 
     **/

    public function addToCart(?Product $product, $quantity = 1)
    {
        $product = $product->id ? $product : $this->product;
        if (config('custom.skip_quantity_checks') || $product->quantity)
        {
            if(!config('custom.skip_quantity_checks'))
                $quantity = $product->quantity >= $quantity ? $quantity : $product->quantity;
            $item =Cart::instance($this->cartInstance)->add($product, $quantity);
            if($product->tax_rate) Cart::instance($this->cartInstance)->setTax($item->rowId, $product->tax_rate);
            $this->persist($this->cartInstance);
            $this->notifyCart();
            $this->notifyBanner(__('shopping_cart.added.cart'));
        }
        else
        {
            $this->notifyBanner(__('shopping_cart.out_of_stock'), 'danger');
        }
    }

    public function addToWishlist(?Product $product)
    {
        $product = $product->id ? $product : $this->product;
        if (!$this->wishlistContains($product)) {
            Cart::instance($this->wishlistInstance)->add($product, 1);
            $this->persist($this->wishlistInstance);
            $this->notifyWishlist();
            $this->notifyBanner(__('shopping_cart.added.wishlist'));
        }
    }


    /** 
     * 
     *      UPDATE
     * 
     **/

    public function updateCartProductQty($rowId, $qty)
    {
        $newQty=Cart::instance($this->cartInstance)->get($rowId)->qty;
        if (config('custom.skip_quantity_checks') || Cart::instance($this->cartInstance)->get($rowId)->model->quantity >= $qty)
        {
            Cart::instance($this->cartInstance)->update($rowId, $qty);
            $newQty = $qty;
            $this->persist($this->cartInstance);
            $this->notifyCart();
        }
        else
        {
            $this->notifyBanner(
                trans_choice(
                    'shopping_cart.left_quantity', 
                    Cart::instance($this->cartInstance)->get($rowId)->model->quantity
                ), 'danger');
        }

        return $newQty;
    }


    /** 
     * 
     *      MOVE
     * 
     **/

    public function moveToCart(Product $product)
    {
        if (config('custom.skip_quantity_checks') || $product->quantity) {
            $item = Cart::instance($this->cartInstance)->add($product, 1);
            if($product->tax_rate) Cart::instance($this->cartInstance)->setTax($item->rowId, $product->tax_rate);
            $this->removeFromWishlist($product);

            $this->persist($this->cartInstance);
            $this->notifyCart();
            $this->notifyBanner(__('shopping_cart.added.cart'));
        }
        else
        {
            $this->notifyBanner(__('shopping_cart.out_of_stock'), 'danger'); 
        }
    }

    public function moveToWishlist(Product $product)
    {
        if (!$this->wishlistContains($product)) {
            Cart::instance($this->wishlistInstance)->add($product, 1);
        }
        $this->removeFromCart($product);

        $this->persist($this->wishlistInstance);
        $this->notifyWishlist();
        $this->notifyBanner(__('shopping_cart.added.wishlist'));
    }


    /** 
     * 
     *      REMOVE
     * 
     **/

    public function removeFromCart(?Product $product)
    {
        if(!$product->id) $product = $this->product;
        $this->remove($this->cartInstance, $product);
        $this->persist($this->cartInstance);
        $this->notifyCart();
    }

    public function removeFromWishlist(?Product $product)
    {
        if(!$product->id) $product = $this->product;
        $this->remove($this->wishlistInstance, $product);
        $this->persist($this->wishlistInstance);
        $this->notifyWishlist();
    }
    
    public function remove(string $instance, Product $product)
    {
        $items = Cart::instance($instance)->search(function ($item, $rowId) use ($product) {
            return $item->id === $product->id;
        });
        foreach( $items as $item)
            Cart::instance($instance)->remove($item->rowId);
    }


    /** 
     * 
     *      CLEAR
     * 
     **/

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
     **/

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
     **/

    public function persist($instance)
    {
        if(Auth::check()) {
            try{
                Cart::instance($instance)->erase(Auth::user()->email);
            }
            catch(Exception $e){
                Log::error("Error erasing ".$instance." in WithSoppingLists [". Auth::user()->email ."]");
            }
            try{
                Cart::instance($instance)->store(Auth::user()->email);
            }
            catch(Exception $e){
                Log::error("Error storing (1) ".$instance." in WithSoppingLists [". Auth::user()->email ."]");
            }
            try{
                Cart::instance($instance)->restore(Auth::user()->email);
            }
            catch(Exception $e){
                Log::error("Error restoring ".$instance." in WithSoppingLists [". Auth::user()->email ."]");
            }
            try{
                Cart::instance($instance)->store(Auth::user()->email);
            }
            catch(Exception $e){
                Log::error("Error storing (2) ".$instance." in WithSoppingLists [". Auth::user()->email ."]");
            }
        }
    }

    
    /** 
     * 
     *      UTILITIES
     * 
     **/

    public function cartContains(Product $product)
    {
        return $this->contains($this->cartInstance, $product);
    }

    public function wishlistContains(Product $product)
    {
        return $this->contains($this->wishlistInstance, $product);
    }

    public function contains(string $instance, Product $product)
    {
        return Cart::instance($instance)->search(function ($item, $row) use ($product) {
            return $item->id === $product->id;
        })->isNotEmpty();
    }

    public function areCartProductsAvaiable()
    {
        $avaiable = true;
        if (!config('custom.skip_quantity_checks')) {
            foreach (Cart::instance($this->cartInstance)->content() as $key=>$item) {
                if ($item->model->quantity < $item->qty) {
                    $avaiable = false;
                }
            }
        }
        return $avaiable;
    }

    public function maxAvaiableFrom()
    {
        $max_avaiable_from = null;
        foreach(Cart::instance($this->cartInstance)->content() as $key=>$item)
        {
            if($item->model->avaiable_from > $max_avaiable_from && $item->model->avaiable_from > today()) 
                $max_avaiable_from = $item->model->avaiable_from;
        }
        return $max_avaiable_from;
    }

}