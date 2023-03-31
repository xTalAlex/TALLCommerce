<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PriceTotal extends Component
{

    public $heading;
    public $subtotal;
    public $discountedSubtotal;
    public $originalTotal;
    public $tax;
    public $total;
    public $coupon;
    public $shipping;
    public $shippingPrice;
    public $products;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($subtotal, $discountedSubtotal, $originalTotal, $tax, $total, $coupon, $shipping = null, $shippingPrice = null, $heading = null, $products = null)
    {
        $this->heading = $heading;
        $this->subtotal = $subtotal;
        $this->discountedSubtotal = $discountedSubtotal;
        $this->originalTotal = $originalTotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->coupon = $coupon;
        $this->shipping = $shipping;
        $this->shippingPrice = $shippingPrice;
        $this->products = $products;
    }

    public function discount()
    {
        if($this->coupon && $this->coupon->applyBeforeTax())
            $discount = $this->subtotal - $this->discountedSubtotal;
        elseif( $this->coupon && !$this->coupon->applyBeforeTax())
            $discount = $this->originalTotal - $this->total;
        return $discount;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.price-total');
    }
}
