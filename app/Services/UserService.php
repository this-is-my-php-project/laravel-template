<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\UserRepository;
use Exception;

class UserService
{
    protected UserRepository $userRepository;

    /**
     * Constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all data
     * @param array $params
     * @return Collection
     */
    public function lists(array $params = []): Collection
    {
        return $this->userRepository->lists($params);
    }

    /**
     * Create data
     * @param array $params
     * @return Model
     */
    public function createOne(array $params): Model
    {
        return $this->userRepository->createOne($params);
    }

    /**
     * Get data by id
     * @param int|string $id
     * @return Model|null
     */
    public function show(int|string $id): ?Model
    {
        $model = $this->userRepository->findById($id);
        if (empty($model)) {
            throw new Exception('Not Found', 404);
        }
        return $this->userRepository->findById($id);
    }

    /**
     * Update data
     * @param int|string $id
     * @param array $params
     * @return Model|null
     */
    public function updateOne(int|string $id, array $params): ?Model
    {
        $model = $this->userRepository->findById($id);
        if (empty($model)) {
            throw new Exception('Not Found', 404);
        }
        return $this->userRepository->updateOne($model, $params);
    }

    /**
     * Delete data
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        $model = $this->userRepository->findById($id);
        if (empty($model)) {
            throw new Exception('Not Found', 404);
        }
        return $this->userRepository->deleteOne($model);
    }
}
