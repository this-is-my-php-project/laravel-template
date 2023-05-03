<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait BaseRepository
{
    /**
     * @var $model Model
     */
    protected $model;

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * @param array $params
     * @param Builder $beforeQuery
     * @return LengthAwarePaginator
     */
    public function getList(array $params, Builder $beforeQuery = null)
    {
        if (empty($params['no_paginate'])) {
            $perPage = $params['per_page'] ?? 20;
            $page = $params['page'] ?? 1;
        }

        $fields = $options['fields'] ?? [];
        $sorts = $params['sorts'] ?? [];
        $relations = $params['relations'] ?? [];

        if (!empty($relations)) {
            $relations = explode(',', $relations);
        }

        if (!empty($beforeQuery)) {
            $this->model = $beforeQuery;
        }

        return $this->model
            ->select($this->getSelectFields($fields))
            ->when(!empty($sorts), $this->sorts($sorts))
            ->with($relations)
            ->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
    }

    /**
     * @param array $sorts
     * @return callable
     */
    public function sorts(array $sorts = []): callable
    {
        return function (Builder $query) use ($sorts) {
            foreach ($sorts as $field => $sort) {
                $query->orderBy($field, $sort);
            }
        };
    }

    /**
     * if fields is empty, return all fields
     * if fields is not empty, loop through fields 
     * remove all special characters except A-Z, a-z, 0-9,
     * 
     * @param array $fields
     * @return array
     */
    public function getSelectFields(array $fields = []): array
    {
        if (empty($fields)) {
            return ['*'];
        }

        return array_map(
            fn ($field) => preg_replace('/[^a-zA-Z0-9_*]/', '', $field),
            $fields
        );
    }

    /**
     * @param LengthAwarePaginator $data
     * @return Collection
     */
    public function pageList(LengthAwarePaginator $data): Collection
    {
        return collect([
            'meta' => [
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ],
            'lists' => $data->items(),
        ]);
    }

    /**
     * Create one record
     * @param array $payload
     * @return null|Model
     */
    public function createOne(array $payload)
    {
        $model = $this->model->create($payload);

        return $model;
    }

    /**
     * Update one record
     * @param Model $model
     * @param array $payload
     * @return null|Model
     */
    public function updateOne(Model $model, array $payload)
    {
        if (!$model) {
            return null;
        }

        $model->fill($payload);
        $model->save();

        return $model;
    }

    /**
     * Destroy row
     * @param string $modelId
     * @return bool
     */
    public function deleteOne(string $modelId): bool
    {
        $model = $this->findById($modelId);
        return $model->delete();
    }

    /**
     * Destroy multiple rows
     * @param array $modelIds
     * @return bool
     */
    public function deleteMultipleByField(string $field, $value): bool
    {
        $model = $this->model->where($field, $value);
        return $model->delete();
    }

    /**
     * Find model by id.
     *
     * @param string $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        string $modelId, //int and string
        array  $columns = ['*'],
        array  $relations = [],
        array  $appends = []
    ): ?Model {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->findOrFail($modelId)
            ->append($appends);
    }

    /**
     * Find by field.
     * @param string $field
     * @param $value
     * @param array $columns
     * @return Model|null
     */
    public function findByField(string $field, $value, array $columns = ['*']): ?Model
    {
        return $this->model->select($columns)->where($field, $value)->first();
    }

    /**
     * Find by multiple columns.
     * @param array $where
     * @param array $columns
     * @param array $relations
     * @param bool $lockForUpdate
     * @return Model|null
     */
    public function findByMultiple(
        array $where = [],
        array $columns = ['*'],
        array $relations = [],
        bool  $lockForUpdate = false
    ): ?Model {
        $query = $this->model->select($columns)->with($relations);
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_numeric($key)) {
                    $query = $query->where([$value]);
                } else {
                    if (is_array($value) && count($value) > 0) {
                        $query = $query->whereIn($key, $value);
                    } elseif (is_string($value) || is_numeric($value)) {
                        $query = $query->where($key, $value);
                    }
                }
            }
        }
        if ($lockForUpdate) {
            $query = $query->lockForUpdate();
        }
        $query = $query->first();
        return $query;
    }

    /**
     * @param string $keyField
     * @param string $valueField
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getByField(
        string $keyField,
        string $valueField,
        array  $columns = ['*'],
        array  $relations = []
    ): Collection {
        return $this->model->select($columns)->with($relations)->where($keyField, $valueField)->get();
    }

    /**
     * @param array $where
     * @param array $columns
     * @param array $relations
     * @return mixed
     */
    public function getByMultiple(
        array $where,
        array $columns = ['*'],
        array $relations = []
    ) {
        $query = $this->model->select($columns)->with($relations);
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_numeric($key)) {
                    $query = $query->where([$value]);
                } else {
                    if (is_array($value) && count($value) > 0) {
                        $query = $query->whereIn($key, $value);
                    } elseif (is_string($value) || is_numeric($value)) {
                        $query = $query->where($key, $value);
                    }
                }
            }
        }
        return $query->get();
    }
}
