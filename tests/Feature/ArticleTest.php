<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_makale_goruntule()
    {
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $article = Article::factory()->state([
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])->create();

        $this->getJson(route('article.show', ['article' => $article->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'body',
                    'category_id',
                    'author_id',
                    'created_at',
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_makale_listele()
    {
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        Article::factory()->count(30)->state([
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])->create();

        $this->getJson(route('article.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'list' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'body',
                            'category_id',
                            'author_id',
                            'created_at',
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

        $this->assertDatabaseCount('articles', 30);
    }

    /**
     * @return void
     */
    public function test_makale_ekle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $article = Article::factory()
            ->hasTags(3)
            ->state([
                'category_id' => $category->id,
                'author_id' => $author->id,
            ])->raw();

        $this->postJson(route('article.store'), $article)
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'body',
                    'category_id',
                    'author_id',
                    'created_at',
                ],
            ]);

        unset($article['tag_ids']);

        $this->assertDatabaseHas('articles', $article);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_makale_ekleyemez()
    {
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $article = Article::factory()->state([
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])->raw();

        $this->postJson(route('article.store'), $article)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_makale_ekleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $article = Article::factory()->state([
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])->raw();

        $this->postJson(route('article.store'), $article)
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_makale_guncelle()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()
            ->hasTags(3)
            ->state([
                'category_id' => $category_insert->id,
                'author_id' => $author_insert->id,
            ])->create();

        $category_update = Category::factory()->create();
        $author_update = Author::factory()->create();
        $article_update = Article::factory()->state([
            'category_id' => $category_update->id,
            'author_id' => $author_update->id,
        ])->raw();

        $article_update = array_merge($article_update, ['tag_ids' => [1, 2]]);

        $this->putJson(
            route('article.update', ['article' => $article_insert->id]),
            $article_update
        )
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'body',
                    'category_id',
                    'author_id',
                    'created_at',
                ],
            ]);

        unset($article_update['tag_ids']);

        $this->assertDatabaseHas('articles', $article_update);
        $this->assertDatabaseCount('taggables', 2);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_makale_guncelleyemez()
    {
        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()->state([
            'category_id' => $category_insert->id,
            'author_id' => $author_insert->id,
        ])->create();

        $category_update = Category::factory()->create();
        $author_update = Author::factory()->create();
        $article_update = Article::factory()->state([
            'category_id' => $category_update->id,
            'author_id' => $author_update->id,
        ])->raw();

        $this->putJson(
            route('article.update', ['article' => $article_insert->id]
            ), $article_update)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_makale_guncelleyemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()->state([
            'category_id' => $category_insert->id,
            'author_id' => $author_insert->id,
        ])->create();

        $category_update = Category::factory()->create();
        $author_update = Author::factory()->create();
        $article_update = Article::factory()->state([
            'category_id' => $category_update->id,
            'author_id' => $author_update->id,
        ])->raw();

        $this->putJson(
            route('article.update', ['article' => $article_insert->id]
            ), $article_update)
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_makale_sil()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()->state([
            'category_id' => $category_insert->id,
            'author_id' => $author_insert->id,
        ])->create();

        $this->deleteJson(
            route('article.destroy', ['article' => $article_insert->id])
        )->assertStatus(200);

        $this->assertDatabaseMissing('articles', ['id' => $article_insert->id]);
    }

    /**
     * @return void
     */
    public function test_oturumu_kapali_kullanici_makale_silemez()
    {
        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()->state([
            'category_id' => $category_insert->id,
            'author_id' => $author_insert->id,
        ])->create();

        $this->deleteJson(
            route('article.destroy', ['article' => $article_insert->id])
        )->assertStatus(401);

        $this->assertDatabaseHas('articles', ['id' => $article_insert->id]);
    }

    /**
     * @return void
     */
    public function test_hesabi_dogrulanmamis_kullanici_makale_silemez()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()->state([
            'category_id' => $category_insert->id,
            'author_id' => $author_insert->id,
        ])->create();

        $this->deleteJson(
            route('article.destroy', ['article' => $article_insert->id])
        )->assertStatus(403);

        $this->assertDatabaseHas('articles', ['id' => $article_insert->id]);
    }

    /**
     * @return void
     */
    public function test_makale_etiketleri()
    {
        $category_insert = Category::factory()->create();
        $author_insert = Author::factory()->create();
        $article_insert = Article::factory()->state([
            'category_id' => $category_insert->id,
            'author_id' => $author_insert->id,
        ])->create();

        $this->assertInstanceOf(Collection::class, $article_insert->tags);
    }
}
