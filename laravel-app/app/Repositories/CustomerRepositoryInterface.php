<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface
{
    /**
     * @param int $id
     * @return Customer|null
     */
    public function find(int $id): ?Customer;

    /**
     * @param int $id
     * @return Customer
     */
    public function findOrFail(int $id): Customer;

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator;

    /**
     * @param string $phoneE164
     * @return Customer|null
     */
    public function findByPhone(string $phoneE164): ?Customer;

    /**
     * @param string $email
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer;

    /**
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer;

    /**
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function update(Customer $customer, array $data): Customer;
}
