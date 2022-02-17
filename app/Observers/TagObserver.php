<?php

namespace App\Observers;

use App\Models\Tag;
use Illuminate\Support\Facades\Artisan;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function created(Tag $tag)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Tag "updated" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        Artisan::call('cache:clear');
    }

    /**
     * Handle the Tag "deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        Artisan::call('cache:clear');
    }
}
