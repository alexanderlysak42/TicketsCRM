<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->firstOrCreate(['name' => 'manager']);
        Role::query()->firstOrCreate(['name' => 'admin']);
    }
}
