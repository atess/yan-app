<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Artisan;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        Artisan::call('cache:clear');
    }
}
