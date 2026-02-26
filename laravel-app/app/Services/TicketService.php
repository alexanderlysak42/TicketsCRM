<?php

namespace App\Services;

use App\Enums\TicketStatusesEnum;
use App\Models\Ticket;
use App\Repositories\TicketRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $tickets,
        private CustomerService $customerService,
    ) {}

    /**
     * @throws Throwable
     */
    public function createFromWidget(array $payload, array $files = []): Ticket
    {
        return DB::transaction(function () use ($payload, $files) {
            $customer = $this->customerService->firstOrCreateByPhone($payload['customer']);

            $ticket = $this->tickets->create([
                'customer_id' => $customer->id,
                'subject' => $payload['subject'],
                'message' => $payload['message'],
                'status' => TicketStatusesEnum::NEW,
                'answered_at' => null,
            ]);

            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }

            return $ticket;
        });
    }

    public function markInProgress(Ticket $ticket): Ticket
    {
        return $this->tickets->update($ticket, [
            'status' => TicketStatusesEnum::IN_PROGRESS,
            'answered_at' => null,
        ]);
    }

    public function markDone(Ticket $ticket): Ticket
    {
        return $this->tickets->update($ticket, [
            'status' => TicketStatusesEnum::DONE,
            'answered_at' => now(),
        ]);
    }
}
