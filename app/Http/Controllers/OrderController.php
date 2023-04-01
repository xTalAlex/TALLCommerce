<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Traits\Livewire\WithShoppingLists;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class OrderController extends Controller
{

    use WithShoppingLists;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with(['products.media','status'])->placed()
            ->orderBy('id','desc')->paginate(5);
            
        $SEOData = new SEOData(
            title: __('My Orders'),
        );

        return view('order.index', compact('orders','SEOData'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $SEOData = new SEOData(
            title: __('Order').' #'.$order->number,
        );

        return view('order.show', compact('order','SEOData'));
    }

    /**
     * Create a new Cart with same products.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reorder(Order $order)
    {  
        foreach($order->products as $product)
        {
            if (config('custom.skip_quantity_checks') || $product->quantity)
            {
                $quantity = $product->pivot->quantity;
                if(!config('custom.skip_quantity_checks'))
                    $quantity = $quantity <= $product->quantity? $quantity : $product->quantity;
                $item = Cart::instance('default')->add($product, $quantity );
                if($product->tax_rate) Cart::instance('default')->setTax($item->rowId, $product->tax_rate);
            }
        }

        return redirect()->route('cart.index');
    }
}
