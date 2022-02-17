<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * @return void
     */
    public function test_yazar_goruntule()
    {
        $author = Author::factory()->create();

        $this->getJson(route('author.show', ['author' => $author->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'bio',
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_yazar_listele()
    {
        Author::factory()->count(30)->create();

        $this->getJson(route('author.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'list' => [
                        '*' => [
                            'id',
                            'name',
                            'bio',
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

        $this->assertDatabaseCount('authors', 30);
    }
    /**
     * @return void
     */
    public function test_yazar_ekle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $author = Author::factory()->raw();

        $this->postJson(route('author.store'), $author)
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'bio',
                ],
            ]);

        $this->assertDatabaseHas('authors', $author);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_yazar_ekleyemez()
    {
        $author = Author::factory()->raw();

        $this->postJson(route('author.store'), $author)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_yazar_ekleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $author = Author::factory()->raw();

        $this->postJson(route('author.store'), $author)
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_yazar_guncelle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $author_insert = Author::factory()->create();
        $author_update = Author::factory()->raw();

        $this->putJson(
            route('author.update', ['author' => $author_insert->id]),
            $author_update
        )->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'bio',
                ],
            ]);

        $this->assertDatabaseHas('authors', $author_update);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_yazar_guncelleyemez()
    {
        $author_insert = Author::factory()->create();
        $author_update = Author::factory()->raw();

        $this->putJson(
            route('author.update', ['author' => $author_insert->id]),
            $author_update
        )->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_yazar_guncelleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $author_insert = Author::factory()->create();
        $author_update = Author::factory()->raw();

        $this->putJson(
            route('author.update', ['author' => $author_insert->id]),
            $author_update
        )->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_yazar_sil()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $author_insert = Author::factory()->create();

        $this->deleteJson(
            route('author.destroy', ['author' => $author_insert->id])
        )->assertStatus(200);

        $this->assertDatabaseMissing('authors', ['id' => $author_insert->id]);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_yazar_silemez()
    {
        $author_insert = Author::factory()->create();

        $this->deleteJson(
            route('author.destroy', ['author' => $author_insert->id])
        )->assertStatus(401);

        $this->assertDatabaseHas('authors', ['id' => $author_insert->id]);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_yazar_silemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $author_insert = Author::factory()->create();

        $this->deleteJson(
            route('author.destroy', ['author' => $author_insert->id])
        )->assertStatus(403);

        $this->assertDatabaseHas('authors', ['id' => $author_insert->id]);
    }
}
