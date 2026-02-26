<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketStatusesEnum;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketAdminController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
    ) {}

    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'status', 'email', 'phone']);

        $tickets = Ticket::query()
            ->with(['customer'])
            ->filter($filters)
            // ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('manager.tickets.index', [
            'tickets' => $tickets,
            'filters' => $filters,
            'statuses' => [
                TicketStatusesEnum::NEW->value => 'Новая',
                TicketStatusesEnum::IN_PROGRESS->value => 'В работе',
                TicketStatusesEnum::DONE->value => 'Обработана',
            ],
        ]);
    }

    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function show(Ticket $ticket)
    {
        $ticket->loadMissing(['customer']);

        $attachments = method_exists($ticket, 'getMedia')
            ? $ticket->getMedia('attachments')->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->file_name ?: ($m->name ?: 'file-'.$m->id),
                'mime_type' => $m->mime_type,
                'size' => $m->human_readable_size ?? null,
                'size_bytes' => $m->size ?? null,
                'url' => $m->getUrl(),
            ])
            : collect();

        return view('manager.tickets.show', [
            'ticket' => $ticket,
            'attachments' => $attachments,
            'statuses' => [
                TicketStatusesEnum::NEW->value => 'Новая',
                TicketStatusesEnum::IN_PROGRESS->value => 'В работе',
                TicketStatusesEnum::DONE->value => 'Обработана',
            ],
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,in_progress,done'],
        ]);

        $ticket = match ($validated['status']) {
            TicketStatusesEnum::IN_PROGRESS->value => $this->ticketService->markInProgress($ticket),
            TicketStatusesEnum::DONE->value => $this->ticketService->markDone($ticket),
            default => $ticket,
        };

        return redirect()
            ->route('manager.tickets.show', $ticket)
            ->with('success', 'Статус обновлён.');
    }
}
