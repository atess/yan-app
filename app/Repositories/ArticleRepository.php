<?php

namespace App\Repositories;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Models\Article;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    /**
     * Class Constructor
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        parent::__construct($article);
    }
}
