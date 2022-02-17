<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * @var PaginationResource
     */
    protected PaginationResource $pagination;

    /**
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->pagination = new PaginationResource($resource);
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'list' => ArticleResource::collection($this->collection),
            'pagination' => $this->pagination,
        ];
    }
}
