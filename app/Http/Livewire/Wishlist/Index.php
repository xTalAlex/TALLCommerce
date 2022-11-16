<?php

namespace App\Http\Livewire\Wishlist;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Traits\Livewire\WithShoppingLists;

class Index extends Component
{
    use WithShoppingLists;
    
    public $content;
    public $count;

    protected $listeners = [
        'updatedWishlist' => 'mount',
    ];

    public User $user;

    protected $rules = [
        'user.email_verified_at' => 'required',
    ];

    public function save()
    {
        $this->validate();
        $this->user->save();

        session()->flash('flash.banner', 'saved');
        session()->flash('flash.bannerStyle', 'success');

        $this->redirect(route('wishlist.index'));
    }

    public function mount()
    {  
        $this->user = \App\Models\User::first();
        $this->checkProducts();
        $this->content = Cart::instance('wishlist')->content();
        $this->count = Cart::instance('wishlist')->count();
    }

    public function checkProducts()
    {
        foreach(Cart::instance('wishlist')->content() as $key=>$item)
        {
            if (!$item->model)
            {
                Cart::remove($key);
                $this->count = Cart::instance('wishlist')->count();
                //notify user item no more avaiable
            }
        }
    }

    public function render()
    {
        return view('wishlist.index');
    }
}
