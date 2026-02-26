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
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Tickets API'
)]
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
    ) {}

    #[OA\Post(
        path: '/api/tickets',
        summary: 'Create ticket',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'subject', type: 'string'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'files',
                            type: 'array',
                            items: new OA\Items(type: 'string', format: 'binary')
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Tickets'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Ticket created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'ok'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/Ticket'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreTicketRequest $request)
    {
        $payload = $request->payload();
        $files = $request->file('files', []);

        $ticket = $this->ticketService->createFromWidget(
            payload: $payload,
            files: $files,
        );

        return response()->json([
            'status' => 'ok',
            'message' => 'Feedback is sent',
            'data' => new TicketResource($ticket),
        ]);

    }

    #[OA\Get(
        path: '/api/tickets/statistics',
        summary: 'Ticket statistics',
        tags: ['Tickets'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistics',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'ok'),
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/TicketStatistics'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function statistics(): JsonResponse
    {
        $now = Carbon::now();

        $data = [
            'last_24h' => Ticket::createdSince($now->copy()->subDay())->count(),
            'last_7d' => Ticket::createdSince($now->copy()->subDays(7))->count(),
            'last_30d' => Ticket::createdSince($now->copy()->subDays(30))->count(),
        ];

        return response()->json([
            'status' => 'ok',
            'data' => new TicketStatisticsResource($data),
        ]);
    }
}
