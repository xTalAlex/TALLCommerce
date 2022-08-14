<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DestroyForm extends Component
{
    use AuthorizesRequests;
    
    public $order;

    public $confirmingOrderDeletion = false;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function deleteOrder()
    {
        $this->authorize('delete', $this->order);

        if ($this->order->canBeDeleted()){
            $this->order->restock();
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
