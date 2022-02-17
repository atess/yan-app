<?php

namespace App\Contracts\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Check model
     *
     * @param array $where
     * @return bool
     */

    public function check(array $where): bool;

    /**
     * Check model
     *
     * @param string $email
     * @return User
     */
    public function findMail(string $email): User;
}
