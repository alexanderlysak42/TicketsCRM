<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function find(int $id): ?Customer
    {
        return Customer::query()->find($id);
    }

    public function findOrFail(int $id): Customer
    {
        return Customer::query()->findOrFail($id);
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Customer::query()
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findByPhone(string $phoneE164): ?Customer
    {
        return Customer::query()->where('phone', $phoneE164)->first();
    }

    public function findByEmail(string $email): ?Customer
    {
        return Customer::query()->where('email', $email)->first();
    }

    public function create(array $data): Customer
    {
        return Customer::query()->create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->fill($data)->save();

        return $customer->refresh();
    }
}
