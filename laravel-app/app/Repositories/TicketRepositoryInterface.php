<?php

namespace App\Repositories;

use App\Enums\TicketStatusesEnum;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    public function find(int $id): ?Ticket;

    public function findOrFail(int $id): Ticket;

    public function paginate(int $perPage = 20): LengthAwarePaginator;

    public function paginateByStatus(TicketStatusesEnum $status, int $perPage = 20): LengthAwarePaginator;

    public function create(array $data): Ticket;

    public function update(Ticket $ticket, array $data): Ticket;
}
