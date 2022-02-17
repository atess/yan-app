<?php

namespace App\Observers;

use App\Models\Subscription;
use Illuminate\Support\Facades\Artisan;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Subscription "updated" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function updated(Subscription $subscription)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Subscription "deleted" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function deleted(Subscription $subscription)
    {
        Artisan::call('cache:clear');
    }
}
