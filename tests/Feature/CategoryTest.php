<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @return void
     */
    public function test_kategori_goruntule()
    {
        $category = Category::factory()->create();

        $this->getJson(route('category.show', ['category' => $category->id]))
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
    public function test_kategori_listele()
    {
        Category::factory()->count(30)->create();

        $this->getJson(route('category.index'))
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

        $this->assertDatabaseCount('categories', 30);
    }
    /**
     * @return void
     */
    public function test_kategori_ekle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->raw();

        $this->postJson(route('category.store'), $category)
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);

        $this->assertDatabaseHas('categories', $category);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_kategori_ekleyemez()
    {
        $category = Category::factory()->raw();

        $this->postJson(route('category.store'), $category)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_kategori_ekleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->raw();

        $this->postJson(route('category.store'), $category)
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_kategori_guncelle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();
        $category_update = Category::factory()->raw();

        $this->putJson(
            route('category.update', ['category' => $category_insert->id]),
            $category_update
        )->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);

        $this->assertDatabaseHas('categories', $category_update);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_kategori_guncelleyemez()
    {
        $category_insert = Category::factory()->create();
        $category_update = Category::factory()->raw();

        $this->putJson(
            route('category.update', ['category' => $category_insert->id]),
            $category_update
        )->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_kategori_guncelleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();
        $category_update = Category::factory()->raw();

        $this->putJson(
            route('category.update', ['category' => $category_insert->id]),
            $category_update
        )->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_kategori_sil()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();

        $this->deleteJson(
            route('category.destroy', ['category' => $category_insert->id])
        )->assertStatus(200);

        $this->assertDatabaseMissing('categories', ['id' => $category_insert->id]);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_kategori_silemez()
    {
        $category_insert = Category::factory()->create();

        $this->deleteJson(
            route('category.destroy', ['category' => $category_insert->id])
        )->assertStatus(401);

        $this->assertDatabaseHas('categories', ['id' => $category_insert->id]);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_kategori_silemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();

        $this->deleteJson(
            route('category.destroy', ['category' => $category_insert->id])
        )->assertStatus(403);

        $this->assertDatabaseHas('categories', ['id' => $category_insert->id]);
    }
}
