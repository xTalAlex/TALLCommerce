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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show(Order $order)
    {
        $this->authorize('viewInvoice', $order);

        $customer = new Buyer($order->invoiceBuyer());

        $items = [];
        foreach ($order->products as $product) {
            $newItem = (new InvoiceItem())
                ->title($product->name)
                ->pricePerUnit($product->pivot->price)
                ->quantity($product->pivot->quantity)
                ->tax( $product->pivot->tax_rate ?? config('cart.tax') , true);
            if( $order->coupon && $order->coupon->appliesBeforeTax() ) 
                $newItem->discount($product->pivot->discount ?? 0);
            array_push($items,$newItem);
        }

        $invoice = Invoice::make()
            ->logo(public_path('img/logo.png'))
            ->series($order->invoice_series ?? 'invalid')
            ->sequence($order->invoice_sequence ?? 0)
            ->buyer($customer)
            ->shipping($order->shipping_price)
            ->addItems($items);
        
        if( $order->coupon && !$order->coupon->appliesBeforeTax() )  $invoice->totalDiscount($order->coupon_discount);

        return $invoice->stream();
    }
}
