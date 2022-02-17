<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * @var ArticleRepositoryInterface
     */
    protected ArticleRepositoryInterface $articleRepository;

    /**
     * @param ArticleRepositoryInterface $repository
     */
    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->articleRepository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            new ArticleCollection(
                $this->articleRepository->paginate()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreArticleRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new ArticleResource(
                $this->articleRepository->create($validated)
            ),
            __('article.created'),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @throws ModelNotFoundException
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        return $this->success(
            new ArticleResource(
                $this->articleRepository->find($article->id)
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArticleRequest $request
     * @param Article $article
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $validated = $request->validated();

        return $this->success(
            new ArticleResource(
                $this->articleRepository->update($validated, $article->id)
            ),
            __('article.updated'),
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Article $article): JsonResponse
    {
        $this->articleRepository->destroy($article->id);

        return $this->success(
            null,
            __('article.deleted')
        );
    }
}
