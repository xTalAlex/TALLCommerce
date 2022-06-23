<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;

class DestroyForm extends Component
{

    public $order;

    public $confirmingOrderDeletion = false;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function deleteOrder()
    {
        if(!auth()->user() || auth()->user()->id !== $this->order->user->id)
            abort(403);

        if ($this->order->canBeDeleted()){
            $this->order->restockProducts();
            $this->order->delete();
            session()->flash('flash.banner', __('banner_notifications.order_deleted') );
            session()->flash('flash.bannerStyle', 'success');
        }
        else{
            session()->flash('flash.banner', __('banner_notifications.order.not_deleted') );
            session()->flash('flash.bannerStyle', 'danger');
        }

        return redirect()->route('order.index');
    }

    public function render()
    {
        return view('order.destroy-form');
    }
}
