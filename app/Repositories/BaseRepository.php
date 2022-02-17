<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    /**
     * Class Constructor
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Paginate all
     *
     * @param integer $perPage
     * @param array $where
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 10, array $where = [], array $columns = ['*']): LengthAwarePaginator
    {
        $key = $this->model->getTable() . "." . $perPage;

        foreach ($columns as $column) {
            if ($column != '*')
                $key.= '.' . $column;
        }

        if (count($where) > 0)
            foreach ($where as $k => $v) {
                $key.= '.' . $k.'-'.$v;
            }

        $requestParams = request()->all();
        if (count($requestParams) > 0)
            foreach ($requestParams as $k => $v) {
                $key.= '.' . $k.'-'.$v;
            }

        return Cache::remember($key, 60, function () use ($where, $columns, $perPage) {
            if (is_array($where) && count($where) > 0)
                $this->model = $this->model->where($where);

            return $this->model->paginate($perPage, $columns);
        });
    }

    /**
     * Create new model
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function create(array $data = []): Model
    {
        try {
            DB::beginTransaction();

            $model = $this->model->create(Arr::except($data, ['tag_ids']));

            if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
                $model->tags()->sync($data['tag_ids']);
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return $model;
    }

    /**
     * Update model by the given ID
     *
     * @param array $data
     * @param integer $id
     * @param string $attribute
     * @return mixed
     * @throws Exception
     */
    public function update(array $data, int $id, string $attribute = 'id'): Model
    {
        try {
            DB::beginTransaction();

            $model = $this->model->find($id);

            if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
                $model->tags()->sync($data['tag_ids']);
            }

            $this->model->where($attribute, $id)->update(Arr::except($data, ['tag_ids']));

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();

            throw new Exception($ex->getMessage());
        }

        return $model->fresh();
    }

    /**
     * Delete model by the given ID
     *
     * @param integer $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            $this->model->destroy($id);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return true;
    }

    /**
     * Find model by the given ID
     *
     * @param integer $id
     * @param array $columns
     * @throws ModelNotFoundException
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']): Model
    {
        $key = $this->model->getTable() . "." . $id;

        foreach ($columns as $column) {
            if ($column != '*')
                $key.= '.' . $column;
        }

        return Cache::remember($key, 60, function () use ($id, $columns) {
            return $this->model->findOrFail($id, $columns);
        });
    }
}
