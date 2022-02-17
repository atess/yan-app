<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\Tag\TagCollection;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagController extends Controller
{
    /**
     * @var TagRepositoryInterface
     */
    protected TagRepositoryInterface $tagRepository;

    /**
     * @param TagRepositoryInterface $repository
     */
    public function __construct(TagRepositoryInterface $repository)
    {
        $this->tagRepository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            new TagCollection(
                $this->tagRepository->paginate()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTagRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new TagResource(
                $this->tagRepository->create($validated)
            ),
            __('tag.created'),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function show(Tag $tag): JsonResponse
    {
        return $this->success(
            new TagResource(
                $this->tagRepository->find($tag->id)
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new TagResource(
                $this->tagRepository->update($validated, $tag->id)
            ),
            __('tag.updated'),
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $this->tagRepository->destroy($tag->id);

        return $this->success(
            null,
            __('tag.deleted')
        );
    }
}
