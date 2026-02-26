<?php

namespace App\Repositories;

use App\Enums\TicketStatusesEnum;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketRepository implements TicketRepositoryInterface
{
    public function find(int $id): ?Ticket
    {
        return Ticket::query()->find($id);
    }

    public function findOrFail(int $id): Ticket
    {
        return Ticket::query()->findOrFail($id);
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Ticket::query()
            ->with('customer')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function paginateByStatus(TicketStatusesEnum $status, int $perPage = 20): LengthAwarePaginator
    {
        return Ticket::query()
            ->with('customer')
            ->where('status', $status) // enum cast
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function create(array $data): Ticket
    {
        return Ticket::query()->create($data);
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->fill($data)->save();

        return $ticket->refresh();
    }
}
