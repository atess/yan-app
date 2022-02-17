<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * @return void
     */
    public function test_abonelik_goruntule()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $subscription = Subscription::factory()->create();

        $this->getJson(route('subscription.show', ['subscription' => $subscription->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_abonelik_goruntuleyemez()
    {
        $subscription = Subscription::factory()->create();

        $this->getJson(route('subscription.show', ['subscription' => $subscription->id]))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_abonelik_goruntuleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $subscription = Subscription::factory()->create();

        $this->getJson(route('subscription.show', ['subscription' => $subscription->id]))
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_abonelik_listele()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Subscription::factory()->count(30)->create();

        $this->getJson(route('subscription.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'list' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                        ]
                    ],
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages',
                    ]
                ]
            ]);

        $this->assertDatabaseCount('subscriptions', 30);
    }


    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_abonelik_listeleyemez()
    {
        Subscription::factory()->create();

        $this->getJson(route('subscription.index'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_abonelik_listeleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        Subscription::factory()->create();

        $this->getJson(route('subscription.index'))
            ->assertStatus(403);
    }


    /**
     * @return void
     */
    public function test_abonelik_ekle()
    {
        $subscription = Subscription::factory()->raw();

        $this->postJson(route('subscription.store'), $subscription)
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('subscriptions', $subscription);
    }

    /**
     * @return void
     */
    public function test_abonelik_guncelle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $subscription_insert = Subscription::factory()->create();
        $subscription_update = Subscription::factory()->raw();

        $this->putJson(
            route('subscription.update', ['subscription' => $subscription_insert->id]),
            $subscription_update
        )->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);

        $this->assertDatabaseHas('subscriptions', $subscription_update);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_abonelik_guncelleyemez()
    {
        $subscription_insert = Subscription::factory()->create();
        $subscription_update = Subscription::factory()->raw();

        $this->putJson(
            route('subscription.update', ['subscription' => $subscription_insert->id]),
            $subscription_update
        )->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_abonelik_guncelleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $subscription_insert = Subscription::factory()->create();
        $subscription_update = Subscription::factory()->raw();

        $this->putJson(
            route('subscription.update', ['subscription' => $subscription_insert->id]),
            $subscription_update
        )->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_abonelik_sil()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $subscription_insert = Subscription::factory()->create();

        $this->deleteJson(
            route('subscription.destroy', ['subscription' => $subscription_insert->id])
        )->assertStatus(200);

        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription_insert->id]);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_abonelik_silemez()
    {
        $subscription_insert = Subscription::factory()->create();

        $this->deleteJson(
            route('subscription.destroy', ['subscription' => $subscription_insert->id])
        )->assertStatus(401);

        $this->assertDatabaseHas('subscriptions', ['id' => $subscription_insert->id]);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_abonelik_silemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $subscription_insert = Subscription::factory()->create();

        $this->deleteJson(
            route('subscription.destroy', ['subscription' => $subscription_insert->id])
        )->assertStatus(403);

        $this->assertDatabaseHas('subscriptions', ['id' => $subscription_insert->id]);
    }
}
