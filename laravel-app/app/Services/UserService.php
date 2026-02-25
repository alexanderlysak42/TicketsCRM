<?php

namespace App\Services;

use App\Enums\UserRolesEnum;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    /**
     * @param UserRepositoryInterface $users
     */
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function createManager(string $name, string $email, string $password): User
    {
        return $this->users->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => UserRolesEnum::MANAGER,
        ]);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function createAdmin(string $name, string $email, string $password): User
    {
        return $this->users->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => UserRolesEnum::ADMIN,
        ]);
    }
}
