<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        Customer::query()->chunk(50, function ($customers) {
            foreach ($customers as $customer) {
                Ticket::factory()
                    ->count(random_int(1, 4))
                    ->for($customer)
                    ->create();
            }
        });
    }
}
