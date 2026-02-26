<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Customer',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Иван Иванов'),
        new OA\Property(property: 'phone', type: 'string', example: '+380991234567'),
        new OA\Property(property: 'email', type: 'string', example: 'ivan@example.com'),
    ],
    type: 'object'
)]
class CustomerSchema {}
