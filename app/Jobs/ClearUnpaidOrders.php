<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ClearUnpaidOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $failed_status = OrderStatus::where('name',insensitiveLike(),'payment_failed')->first();
        $cancelled_status = OrderStatus::where('name',insensitiveLike(),'cancelled')->first();
        $draft_status = OrderStatus::where('name',insensitiveLike(),'draft')->first();
        if($failed_status && $cancelled_status && $draft_status)
        {
            $orders = Order::with('history')
                ->where('orders.order_status_id', $failed_status->id )
                ->whereHas('history', fn($query) => 
                    $query->where('order_histories.order_status_id', $failed_status->id)->latest()
                        ->whereDate('created_at', '<=' , Carbon::now()->subDays(7))
                )
                ->get();
            foreach($orders as $order)
            {
                $order->setAsCancelled();
            }

            $orders = Order::with('history')
                ->where('orders.order_status_id', $draft_status->id )
                ->whereHas('history', fn($query) => 
                    $query->where('order_histories.order_status_id', $draft_status->id)->latest()
                        ->whereDate('created_at', '<=' , Carbon::now()->subDays(7))
                )
                ->delete();
        }
        else
        {
            if(!$failed_status)
                Log::error('OrderStatuses "cancelled" not found.');
            if(!$draft_status)
                Log::error('OrderStatus "draft" not found.');
            if(!$cancelled_status)
                Log::error('OrderStatus "payment_failed" not found.');
        }
    }
}
