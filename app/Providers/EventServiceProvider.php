<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        \App\Models\User::class => [\App\Observers\UserObserver::class],
        \App\Models\Product::class => [\App\Observers\ProductObserver::class],
        \App\Models\Order::class => [\App\Observers\OrderObserver::class],
        \App\Models\Category::class => [\App\Observers\CategoryObserver::class],
        \App\Models\Brand::class => [\App\Observers\BrandObserver::class],
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
