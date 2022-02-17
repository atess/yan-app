<?php

namespace App\Repositories;

use App\Contracts\Repositories\SubscriptionRepositoryInterface;
use App\Models\Subscription;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    /**
     * Class Constructor
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        parent::__construct($subscription);
    }
}
