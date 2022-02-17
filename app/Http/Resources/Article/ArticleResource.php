<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Tag\TagCollection;
use App\Models\Author;
use App\Models\Category;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $body
 * @property int $category_id
 * @property int $author_id
 * @property Date $created_at
 * @property Category $category
 * @property Author $author
 * @property mixed $tags
 */
class ArticleResource extends JsonResource
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
            'description' => $this->description,
            'body' => $this->body,
            'category_id' => $this->category_id,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'category' => $this->whenLoaded('category', CategoryResource::make($this->category)),
            'author' => $this->whenLoaded('author', AuthorResource::make($this->author)),
            'tags' => TagCollection::collection($this->whenLoaded('tags')),
        ];
    }
}
