<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * Class Constructor
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}
