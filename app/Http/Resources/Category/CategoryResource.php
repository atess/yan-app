<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Tag\TagCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property mixed $tags
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tags' => $this->whenLoaded('tags', TagCollection::collection($this->tags)),
        ];
    }
}
