<?php

namespace App\Observers;

use App\Models\Author;
use Illuminate\Support\Facades\Artisan;

class AuthorObserver
{
    /**
     * Handle the Author "created" event.
     *
     * @param Author $author
     * @return void
     */
    public function created(Author $author)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Author "updated" event.
     *
     * @param Author $author
     * @return void
     */
    public function updated(Author $author)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Author "deleted" event.
     *
     * @param Author $author
     * @return void
     */
    public function deleted(Author $author)
    {
        Artisan::call('cache:clear');
    }
}
