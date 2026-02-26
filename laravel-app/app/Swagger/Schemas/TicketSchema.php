<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Ticket',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 123),

        new OA\Property(
            property: 'status',
            properties: [
                new OA\Property(property: 'code', type: 'string', example: 'new'),
                new OA\Property(property: 'label', type: 'string', example: 'Новая'),
            ],
            type: 'object'
        ),

        new OA\Property(property: 'subject', type: 'string', example: 'Не работает форма'),
        new OA\Property(property: 'message', type: 'string', example: 'Описание проблемы'),

        new OA\Property(
            property: 'customer',
            ref: '#/components/schemas/Customer'
        ),

        new OA\Property(
            property: 'attachments',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 10),
                    new OA\Property(property: 'name', type: 'string', example: 'file.pdf'),
                    new OA\Property(property: 'url', type: 'string', format: 'uri'),
                    new OA\Property(property: 'size', type: 'integer', example: 204800),
                    new OA\Property(property: 'mime_type', type: 'string', example: 'application/pdf'),
                ],
                type: 'object'
            )
        ),

        new OA\Property(property: 'answered_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ],
    type: 'object'
)]
class TicketSchema {}
