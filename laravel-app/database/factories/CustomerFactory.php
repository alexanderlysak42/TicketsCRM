<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $uaPhone = '+380' . fake()->numberBetween(500000000, 999999999);

        return [
            'name'  => fake()->name(),
            'phone' => $uaPhone,
            'email' => fake()->optional(0.8, null)->passthrough(fake()->unique()->safeEmail()),
        ];
    }
}
