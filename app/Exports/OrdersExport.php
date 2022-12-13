<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderStatus;
use Maatwebsite\Excel\Concerns\FromQuery;

class OrdersExport implements FromQuery
{
    protected $from;
    protected $paied_status_id;

    public function __construct($from = null)
    {
        $this->from = $from;
        $this->paied_status_id = OrderStatus::where('name','paied')->first()->id;
    }

    public function query()
    {
        return Order::when($this->from, fn ($query) => 
                $query->whereHas('history', fn ($query) => $query->where('order_status_id', $this->paied_status_id))
                    ->where('updated_at','>=', $this->from)
            );
    }
}
