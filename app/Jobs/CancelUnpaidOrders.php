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

class CancelUnpaidOrders implements ShouldQueue
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
        $failed_status = OrderStatus::where('name','like','payment_failed')->first();
        $canceled_status = OrderStatus::where('name','like','canceled')->first();
        if($failed_status && $canceled_status)
        {
            $orders = Order::where('order_status_id', $failed_status->id )
                ->whereDate('updated_at', '<=' , Carbon::now()->sub('days',2))->get();
            foreach($orders as $order)
            {
                $order->status()->associate($canceled_status->id);
                $order->save();
                $order->history()->create([
                    'order_status' => $canceled_status->id,
                ]);
                $order->restock();
            }
        }
        else
        {
            if(!$failed_status && !$canceled_status)
                Log::error('OrderStatuses payment_failed and canceled not found.');
            elseif(!$failed_status)
                Log::error('OrderStatus payment_failed not found.');
            else
                Log::error('OrderStatus canceled not found.');
        }
    }
}
