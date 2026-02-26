<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

readonly class UserService
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    /**
     * @throws Throwable
     */
    public function createManager(string $name, string $email, string $password): User
    {
        return DB::transaction(function () use ($name, $email, $password) {
            $user = $this->users->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $user->assignRole('manager');

            return $user->refresh();
        });
    }

    /**
     * @throws Throwable
     */
    public function createAdmin(string $name, string $email, string $password): User
    {
        return DB::transaction(function () use ($name, $email, $password) {
            $user = $this->users->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $user->assignRole('admin');

            return $user->refresh();
        });
    }
}
