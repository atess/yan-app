<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
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
            'list' => CategoryResource::collection($this->collection),
            'pagination' => $this->pagination,
        ];
    }
}
