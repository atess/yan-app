<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AuthorRepositoryInterface;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\Author\AuthorCollection;
use App\Http\Resources\Author\AuthorResource;
use App\Models\Author;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    protected AuthorRepositoryInterface $authorRepository;

    /**
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->authorRepository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            new AuthorCollection(
                $this->authorRepository->paginate()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAuthorRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new AuthorResource(
                $this->authorRepository->create($validated)
            ),
            __('author.created'),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function show(Author $author): JsonResponse
    {
        return $this->success(
            new AuthorResource(
                $this->authorRepository->find($author->id)
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAuthorRequest $request
     * @param Author $author
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new AuthorResource(
                $this->authorRepository->update($validated, $author->id)
            ),
            __('author.updated'),
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Author $author
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Author $author): JsonResponse
    {
        $this->authorRepository->destroy($author->id);

        return $this->success(
            null,
            __('author.deleted')
        );
    }
}
