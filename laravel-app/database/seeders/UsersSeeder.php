<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->syncRoles(['admin']);

        $manager = User::query()->firstOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager', 'password' => Hash::make('password')]
        );
        $manager->syncRoles(['manager']);
    }
}
