<?php

namespace App\Observers;

use App\Jobs\SubscriptionJob;
use App\Models\Article;
use Illuminate\Support\Facades\Artisan;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     *
     * @param Article $article
     * @return void
     */
    public function created(Article $article)
    {
        SubscriptionJob::dispatch($article);
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Article "updated" event.
     *
     * @param Article $article
     * @return void
     */
    public function updated(Article $article)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Article "deleted" event.
     *
     * @param Article $article
     * @return void
     */
    public function deleted(Article $article)
    {
        Artisan::call('cache:clear');
    }
}
