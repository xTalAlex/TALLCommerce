<?php

namespace App\Jobs\Stripe;

use App\Models\{Order, OrderStatus};
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\WebhookClient\Models\WebhookCall;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PaymentIntentSucceededJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payment_intent=$this->webhookCall->payload['data']['object']['id'];
        $order = Order::where('payment_gateway','stripe')->where('payment_id',$payment_intent)->first();
        if($order)
        {
            if(!$order->setAsPaid()) 
                Log::error('Couldn\'t update status to "paid" for order #'.$order->id .' (Payment Succeeded)');
        }
        else
            Log::error('Order not found for payment intent '.$payment_intent. ' (Payment Succeeded)');
    }
}
