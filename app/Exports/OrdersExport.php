<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderStatus;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersExport implements FromView
{
    protected $shipping;
    protected $from;
    protected $to;
    protected $statuses;

    public function __construct($from = null, $to = null, $statuses = [])
    {
        $this->shipping = Product::withoutGlobalScopes()->where('name',insensitiveLike(),'%consegna%')->first();
        $this->from = $from;
        $this->to = $to;
        $this->statuses = count($statuses) ? $statuses : OrderStatus::all()->pluck('id');
    }

    /**
    * @return Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('exports.orders', [
            'shipping_sku' => $this->shipping?->sku ?? '00.CONSEGNA',
            'orders' => Order::with(['products'])
                ->when($this->from, fn ($query) => 
                    $query->whereHas('history', fn ($query) => $query->whereIn('order_status_id', $this->statuses))
                        ->where('updated_at','>=', $this->from) 
                )
                ->when($this->to, fn ($query) => 
                    $query->whereHas('history', fn ($query) => $query->whereIn('order_status_id', $this->statuses))
                        ->where('updated_at','<=', $this->to) 
                )->get()
        ]);
    }
}
