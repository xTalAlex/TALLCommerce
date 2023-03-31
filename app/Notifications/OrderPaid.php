<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class OrderPaid extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $is_fast;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order->load(['user','products','shippingPrice']);
        $this->is_fast = $order->shippingPrice->isFast();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('We have received your order'))
            ->markdown('mail.order.paid', [
                'order' => $this->order,
            ]);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $url = route('filament.resources.orders.view', $this->order);
        return (new SlackMessage)
                    ->content( ($this->is_fast? ':rocket:' : '' ) . __('New Order'))
                    ->attachment(function ($attachment) use ($url) {
                        $attachment->title('#'.$this->order->number, $url)
                                   ->content(__('From :email', ['email' =>  $this->order->email]));
                    });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
