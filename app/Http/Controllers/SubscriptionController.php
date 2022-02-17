<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\SubscriptionRepositoryInterface;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Http\Resources\Subscription\SubscriptionCollection;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionRepositoryInterface
     */
    protected SubscriptionRepositoryInterface $subscriptionRepository;

    /**
     * @param SubscriptionRepositoryInterface $repository
     */
    public function __construct(SubscriptionRepositoryInterface $repository)
    {
        $this->subscriptionRepository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            new SubscriptionCollection(
                $this->subscriptionRepository->paginate()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubscriptionRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new SubscriptionResource(
                $this->subscriptionRepository->create($validated)
            ),
            __('subscription.created'),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Subscription $subscription
     * @return JsonResponse
     */
    public function show(Subscription $subscription): JsonResponse
    {
        return $this->success(
            new SubscriptionResource(
                $this->subscriptionRepository->find($subscription->id)
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubscriptionRequest $request
     * @param Subscription $subscription
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new SubscriptionResource(
                $this->subscriptionRepository->update($validated, $subscription->id)
            ),
            __('subscription.updated'),
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Subscription $subscription
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Subscription $subscription): JsonResponse
    {
        $this->subscriptionRepository->destroy($subscription->id);

        return $this->success(
            null,
            __('subscription.deleted')
        );
    }
}
