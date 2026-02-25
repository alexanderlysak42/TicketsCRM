<?php

namespace Database\Factories;

use App\Enums\TicketStatusesEnum;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([
            TicketStatusesEnum::NEW,
            TicketStatusesEnum::IN_PROGRESS,
            TicketStatusesEnum::DONE,
        ]);

        return [
            'customer_id' => Customer::factory(),
            'subject'     => fake()->sentence(6),
            'message'     => fake()->paragraphs(2, true),
            'status'      => $status,
            'answered_at' => $status === TicketStatusesEnum::DONE
                ? fake()->dateTimeBetween('-30 days', 'now')
                : null,
        ];
    }
}
