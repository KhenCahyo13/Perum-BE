<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\User;
use Modules\Core\Repositories\Repository;

class UserRepository extends Repository
{
    public function __construct(
        private readonly User $model
    ) {}

    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }
}
