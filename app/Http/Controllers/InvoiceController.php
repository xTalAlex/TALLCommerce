<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('viewInvoice', $order);

        $customer = new Buyer([
            'name'          => $order->billingAddress()->full_name,
            'custom_fields' => [
                'email' => $order->email,
                'order_number' => '#'.$order->number,
            ],
        ]);

        $items = [];
        foreach ($order->products as $product) {
            array_push(
                $items,
                (new InvoiceItem())
                    ->title($product->name)
                    ->pricePerUnit($product->pivot->price)
                    ->quantity($product->pivot->quantity)
            );
        }

        $invoice = Invoice::make()
            ->series($order->invoice_series)
            ->sequence($order->invoice_sequence)
            ->buyer($customer)
            ->taxRate(config('cart.tax'))
            ->shipping($order->shipping_price)
            ->addItems($items);
        
        if($order->coupon_discount > 0) $invoice->totalDiscount($order->coupon_discount);

        return $invoice->stream();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
