<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;

    public function findOrFail(int $id): User;

    public function findByEmail(string $email): ?User;

    public function getByRole(string $roleName): Collection;

    public function create(array $data): User;

    public function update(User $user, array $data): User;
}
