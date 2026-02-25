<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return User::query()->find($id);
    }

    /**
     * @param int $id
     * @return User
     */
    public function findOrFail(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    /**
     * @param string $roleName
     * @return Collection
     */
    public function getByRole(string $roleName): Collection
    {
        return User::query()
            ->role($roleName)
            ->orderBy('id')
            ->get();
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->fill($data)->save();

        return $user->refresh();
    }
}
