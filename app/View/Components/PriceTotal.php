<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PriceTotal extends Component
{

    public $heading;
    public $subtotal;
    public $discountedSubtotal;
    public $tax;
    public $total;
    public $coupon;
    public $shipping;
    public $shippingPrice;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($subtotal, $discountedSubtotal, $tax, $total, $coupon, $shipping = null, $shippingPrice = null, $heading = null)
    {
        $this->heading = $heading;
        $this->subtotal = $subtotal;
        $this->discountedSubtotal = $discountedSubtotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->coupon = $coupon;
        $this->shipping = $shipping;
        $this->shippingPrice = $shippingPrice;
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
