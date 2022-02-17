<?php

namespace App\Repositories;

use App\Contracts\Repositories\AuthorRepositoryInterface;
use App\Models\Author;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    /**
     * Class Constructor
     * @param Author $author
     */
    public function __construct(Author $author)
    {
        parent::__construct($author);
    }
}
