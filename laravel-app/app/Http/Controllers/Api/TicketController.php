<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketStatisticsResource;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Throwable;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
    ) {}

    /**
     * @throws Throwable
     */
    public function store(StoreTicketRequest $request)
    {
        //dd($request->all());
        $payload = $request->payload();
        $files = $request->file('files', []);

        $ticket = $this->ticketService->createFromWidget(
            payload: $payload,
            files: $files,
        );

        return response()->json([
            'status'  => 'ok',
            'message' => 'Feedback is sent',
            'data' => new TicketResource($ticket),
        ]);

    }

    public function statistics(): JsonResponse
    {
        $now = Carbon::now();

        $data = [
            'last_24h' => Ticket::createdSince($now->copy()->subDay())->count(),
            'last_7d'  => Ticket::createdSince($now->copy()->subDays(7))->count(),
            'last_30d' => Ticket::createdSince($now->copy()->subDays(30))->count(),
        ];

        return response()->json([
            'status' => 'ok',
            'data' => new TicketStatisticsResource($data),
        ]);
    }

}
