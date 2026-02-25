<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CustomerService
{
    /**
     * @param CustomerRepositoryInterface $customers
     */
    public function __construct(
        private CustomerRepositoryInterface $customers,
    ) {}

    /**
     * @param array $payload
     * @return Customer
     * @throws Throwable
     */
    public function firstOrCreateByPhone(array $payload): Customer
    {
        return DB::transaction(function () use ($payload) {
            $existing = $this->customers->findByPhone($payload['phone']);
            if ($existing) {
                return $this->customers->update($existing, array_filter([
                    'name' => $payload['name'] ?? $existing->name,
                    'email' => $payload['email'] ?? $existing->email,
                ], fn ($v) => $v !== null));
            }

            return $this->customers->create([
                'name' => $payload['name'],
                'phone' => $payload['phone'],
                'email' => $payload['email'] ?? null,
            ]);
        });
    }
}
