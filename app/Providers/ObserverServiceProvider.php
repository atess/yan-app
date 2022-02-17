<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Subscription;
use App\Models\Tag;
use App\Observers\ArticleObserver;
use App\Observers\AuthorObserver;
use App\Observers\CategoryObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\TagObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Article::observe(ArticleObserver::class);
        Author::observe(AuthorObserver::class);
        Category::observe(CategoryObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        Tag::observe(TagObserver::class);
    }
}
