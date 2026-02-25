<?php

namespace App\Repositories;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User;

    /**
     * @param int $id
     * @return User
     */
    public function findOrFail(int $id): User;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * @param UserRolesEnum $role
     * @return Collection
     */
    public function getByRole(UserRolesEnum $role): Collection;

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User;
}
