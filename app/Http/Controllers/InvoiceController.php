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

        $addressString = $order->billing_address_address.', '.
            $order->billing_address_city. ' ('.$order->billing_address_province.'), '.
            $order->billing_address_postal_code.', '.$order->billing_address_country_region;

        $customer = new Buyer([
            'name'          => $order->billingAddress()->full_name,
            'custom_fields' => [  
                'order_number' => '#'.$order->number,
                'email' => $order->billing_address_full_name,
                'address' => $addressString,
                'fiscal_code' => $order->fiscal_code ? $order->fiscal_code : '-',
                'vat' => $order->vat ? $order->vat : '-',
            ],
        ]);

        $items = [];
        foreach ($order->products as $product) {
            $newItem = (new InvoiceItem())
                ->title($product->name)
                ->pricePerUnit($product->pivot->price)
                ->quantity($product->pivot->quantity)
                ->tax( $product->pivot->tax_rate ?? config('cart.tax') , true);
            if( $order->coupon && $order->coupon->applyBeforeTax() ) 
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
        
        if( $order->coupon && !$order->coupon->applyBeforeTax() )  $invoice->totalDiscount($order->coupon_discount);

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
