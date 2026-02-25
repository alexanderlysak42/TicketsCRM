<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @param int $id
     * @return Customer|null
     */
    public function find(int $id): ?Customer
    {
        return Customer::query()->find($id);
    }

    /**
     * @param int $id
     * @return Customer
     */
    public function findOrFail(int $id): Customer
    {
        return Customer::query()->findOrFail($id);
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Customer::query()
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * @param string $phoneE164
     * @return Customer|null
     */
    public function findByPhone(string $phoneE164): ?Customer
    {
        return Customer::query()->where('phone', $phoneE164)->first();
    }

    /**
     * @param string $email
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer
    {
        return Customer::query()->where('email', $email)->first();
    }

    /**
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer
    {
        return Customer::query()->create($data);
    }

    /**
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->fill($data)->save();

        return $customer->refresh();
    }
}
