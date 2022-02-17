<?php

namespace App\Repositories;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    /**
     * Class Constructor
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }
}
