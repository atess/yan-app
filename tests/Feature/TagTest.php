<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagTest extends TestCase
{
    /**
     * @return void
     */
    public function test_etiket_goruntule()
    {
        $tag = Tag::factory()->create();

        $this->getJson(route('tag.show', ['tag' => $tag->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_etiket_listele()
    {
        Tag::factory()->count(30)->create();

        $this->getJson(route('tag.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'list' => [
                        '*' => [
                            'id',
                            'name',
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

        $this->assertDatabaseCount('tags', 30);
    }
    /**
     * @return void
     */
    public function test_etiket_ekle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $tag = Tag::factory()->raw();

        $this->postJson(route('tag.store'), $tag)
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);

        $this->assertDatabaseHas('tags', $tag);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_etiket_ekleyemez()
    {
        $tag = Tag::factory()->raw();

        $this->postJson(route('tag.store'), $tag)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_etiket_ekleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $tag = Tag::factory()->raw();

        $this->postJson(route('tag.store'), $tag)
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_etiket_guncelle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $tag_insert = Tag::factory()->create();
        $tag_update = Tag::factory()->raw();

        $this->putJson(
            route('tag.update', ['tag' => $tag_insert->id]),
            $tag_update
        )->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);

        $this->assertDatabaseHas('tags', $tag_update);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_etiket_guncelleyemez()
    {
        $tag_insert = Tag::factory()->create();
        $tag_update = Tag::factory()->raw();

        $this->putJson(
            route('tag.update', ['tag' => $tag_insert->id]),
            $tag_update
        )->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_etiket_guncelleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $tag_insert = Tag::factory()->create();
        $tag_update = Tag::factory()->raw();

        $this->putJson(
            route('tag.update', ['tag' => $tag_insert->id]),
            $tag_update
        )->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_etiket_sil()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $tag_insert = Tag::factory()->create();

        $this->deleteJson(
            route('tag.destroy', ['tag' => $tag_insert->id])
        )->assertStatus(200);

        $this->assertDatabaseMissing('tags', ['id' => $tag_insert->id]);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_etiket_silemez()
    {
        $tag_insert = Tag::factory()->create();

        $this->deleteJson(
            route('tag.destroy', ['tag' => $tag_insert->id])
        )->assertStatus(401);

        $this->assertDatabaseHas('tags', ['id' => $tag_insert->id]);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_etiket_silemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $tag_insert = Tag::factory()->create();

        $this->deleteJson(
            route('tag.destroy', ['tag' => $tag_insert->id])
        )->assertStatus(403);

        $this->assertDatabaseHas('tags', ['id' => $tag_insert->id]);
    }
}
