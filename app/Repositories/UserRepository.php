<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Class Constructor
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param array $where
     * @return bool
     */
    public function check(array $where): bool
    {
        return $this->model->where($where)
            ->exists();
    }

    /**
     * @param string $email
     * @return User
     */
    public function findMail(string $email): User
    {
        return $this->model->whereEmail($email)
            ->firstOrFail();
    }
}
