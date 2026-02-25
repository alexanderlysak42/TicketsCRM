<?php

namespace App\Repositories;

use App\Enums\TicketStatusesEnum;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * @param int $id
     * @return Ticket|null
     */
    public function find(int $id): ?Ticket
    {
        return Ticket::query()->find($id);
    }

    /**
     * @param int $id
     * @return Ticket
     */
    public function findOrFail(int $id): Ticket
    {
        return Ticket::query()->findOrFail($id);
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Ticket::query()
            ->with('customer')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * @param TicketStatusesEnum $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateByStatus(TicketStatusesEnum $status, int $perPage = 20): LengthAwarePaginator
    {
        return Ticket::query()
            ->with('customer')
            ->where('status', $status) // enum cast
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * @param array $data
     * @return Ticket
     */
    public function create(array $data): Ticket
    {
        return Ticket::query()->create($data);
    }

    /**
     * @param Ticket $ticket
     * @param array $data
     * @return Ticket
     */
    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->fill($data)->save();

        return $ticket->refresh();
    }
}
