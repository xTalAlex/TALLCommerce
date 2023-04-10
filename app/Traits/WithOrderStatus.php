<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Notifications\OrderPaid;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderShipped;
use App\Notifications\OrderCancelled;
use App\Notifications\OrderCompleted;
use App\Notifications\OrderPaymentFailed;
use Illuminate\Support\Facades\Notification;

trait WithOrderStatus
{

    public function statusCanBecome(String $status)
    {
        $can = false;

        switch(strtolower($status))
        {
            case('payment_failed'):
                $can = $this->status->name == 'pending';
                break;
            case('paid'):
                $can = $this->status->name == 'pending' || $this->status->name == 'draft';
                break;
            case('preparing'):
                $can = $this->status->name == 'paid';
                break;
            case('shipped'):
                $can = $this->status->name == 'paid' || $this->status->name == 'preparing';
                break;
            case('completed'):
                $can = $this->status->name == 'paid' || $this->status->name == 'shipped';
                break;
            case('refunded'):
                $can = $this->status->name == 'completed' || $this->status->name == 'shipped'  || $this->status->name == 'paid';
                break;
            case('cancelled'):
                $can = $this->status->name == 'paid';
                break;
        }

        return $can;
    }

    public function canBeDeleted()
    {
        $deletable_statuses = collect(['payment_failed']);

        return $deletable_statuses->contains($this->status->name);
    }

    public function canBePaid()
    {
        $payable_statuses = collect(['payment_failed']);

        return $payable_statuses->contains($this->status->name);
    }

    public function canBeEdited()
    {
        $editable_statuses = collect(['pending','payment_failed','paid']);

        return $editable_statuses->contains($this->status->name);
    }

    public function canBeInvoiced()
    {
        return strtolower($this->status->name) !='draft' && strtolower($this->status->name) !='pending' && strtolower($this->status->name) !='payment_failed'
            && strtolower($this->status->name) !='refunded' && strtolower($this->status->name) !='cancelled';
    }

    public function isActive()
    {
        $active_statuses = collect(['pending','payment_failed','paid','preparing']);

        return $active_statuses->contains($this->status->name);
    }

    public function setAsPaymentFailed()
    {
        $res = false;

        DB::transaction(function () use(&$res) {
            $status = OrderStatus::where('name','payment_failed')->first();
            if ($status) {
                $this->status()->associate($status);
                $this->save();
                $this->history()->create([
                    'order_status_id' => $status->id,
                ]);
                Notification::route('mail', $this->user?->email ?? $this->email)->notify(new OrderPaymentFailed($this));
                $res = true;
            }
        });

        return $res;
    }

    public function setAsPaid()
    {
        $res = false;

        DB::transaction(function () use(&$res) {
            $status = OrderStatus::where('name','paid')->first();
            if ($status) {
                $lastInvoiceSequence= Order::where('invoice_series', today()->format('y'))->max('invoice_sequence') ?? 0;
                $this->status()->associate($status);
                $this->invoice_sequence = $lastInvoiceSequence + 1;
                $this->invoice_series = today()->format('y');
                $this->save();
                $this->history()->create([
                    'order_status_id' => $status->id,
                ]);
                Notification::route('mail', $this->user?->email ?? $this->email)
                    ->route('slack', config('services.slack.webhook'))
                    ->notify(new OrderPaid($this));
                $res = true;
            }
        });

        return $res;
    }

    public function setAsShipped($tracking_number = null)
    {
        $res = false;

        DB::transaction(function () use(&$res, $tracking_number) {
            $status = OrderStatus::where('name','shipped')->first();
            if ($status) {
                $status_id = \App\Models\OrderStatus::where('name', insensitiveLike(),'shipped')->first()->id;
                $this->status()->associate($status_id);
                $this->tracking_number = $tracking_number;
                $this->save();
                $this->history()->create([
                    'order_status_id' => $status_id,
                ]);
                Notification::route('mail', $this->user?->email ?? $this->email)->notify(new OrderShipped($this));
                $res = true;
            }
        });

        return $res;
    }

    public function setAsCompleted()
    {
        $res = false;

        DB::transaction(function () use(&$res) {
            $status = OrderStatus::where('name','completed')->first();
            if ($status) {
                $this->status()->associate($status);
                $this->save();
                $this->history()->create([
                    'order_status_id' => $status->id,
                ]);
                Notification::route('mail', $this->user?->email ?? $this->email)->notify(new OrderCompleted($this));
                $res = true;
            }
        });

        return $res;
    }

    public function setAsCancelled()
    {
        $res = false;

        DB::transaction(function () use(&$res) {
            $status = OrderStatus::where('name','cancelled')->first();
            if ($status) {
                $this->status()->associate($status);
                $this->save();
                $this->history()->create([
                    'order_status_id' => $status->id,
                ]);
                $this->restock();
                Notification::route('mail', $this->user?->email ?? $this->email)->notify(new OrderCancelled($this));
                $res = true;
            }
        });

        return $res;
    }
}