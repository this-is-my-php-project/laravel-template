<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Traits\BaseRepository;
use App\Models\User;

class UserRepository
{
    use BaseRepository;

    /**
     * @var User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * List all data
     * @param array $params
     * @param array $columns
     * @return Collection
     */
    public function lists(array $params): Collection
    {
        $result = $this->getList($params);

        return $this->pageList($result);
    }

    /**
     * Filter params
     * @param $query
     * @param $params
     * @return mixed
     */
    public function filterParams($query, $params)
    {
        return $query;
    }
}