<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface
{
    public function find(int $id): ?Customer;

    public function findOrFail(int $id): Customer;

    public function paginate(int $perPage = 20): LengthAwarePaginator;

    public function findByPhone(string $phoneE164): ?Customer;

    public function findByEmail(string $email): ?Customer;

    public function create(array $data): Customer;

    public function update(Customer $customer, array $data): Customer;
}
