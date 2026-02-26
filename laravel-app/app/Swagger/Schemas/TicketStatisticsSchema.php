<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TicketStatistics',
    properties: [
        new OA\Property(
            property: 'periods',
            properties: [
                new OA\Property(
                    property: 'last_24h',
                    properties: [
                        new OA\Property(property: 'label', type: 'string', example: 'За сутки'),
                        new OA\Property(property: 'count', type: 'integer', example: 12),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'last_7d',
                    properties: [
                        new OA\Property(property: 'label', type: 'string', example: 'За неделю'),
                        new OA\Property(property: 'count', type: 'integer', example: 87),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'last_30d',
                    properties: [
                        new OA\Property(property: 'label', type: 'string', example: 'За месяц'),
                        new OA\Property(property: 'count', type: 'integer', example: 301),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),

        new OA\Property(
            property: 'meta',
            properties: [
                new OA\Property(
                    property: 'generated_at',
                    type: 'string',
                    format: 'date-time'
                ),
            ],
            type: 'object'
        ),
    ],
    type: 'object'
)]
class TicketStatisticsSchema {}
