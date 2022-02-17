<?php

namespace App\Contracts\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Paginate all
     *
     * @param integer $perPage
     * @param array $where
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 10, array $where = [], array $columns = ['*']): LengthAwarePaginator;

    /**
     * Create new model
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data = []): Model;

    /**
     * Update model by the given ID
     *
     * @param array  $data
     * @param integer $id
     * @param string $attribute
     * @return Model
     */
    public function update(array $data, int $id, string $attribute = 'id'): Model;

    /**
     * Delete model by the given ID
     *
     * @param integer $id
     * @return bool
     */
    public function destroy(int $id): bool;

    /**
     * Find model by the given ID
     *
     * @param integer $id
     * @param array $columns
     * @return Model
     */
    public function find(int $id, array $columns = ['*']): Model;
}
