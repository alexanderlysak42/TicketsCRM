<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'periods' => [
                'last_24h' => [
                    'label' => 'За сутки',
                    'count' => $this->resource['last_24h'] ?? 0,
                ],
                'last_7d' => [
                    'label' => 'За неделю',
                    'count' => $this->resource['last_7d'] ?? 0,
                ],
                'last_30d' => [
                    'label' => 'За месяц',
                    'count' => $this->resource['last_30d'] ?? 0,
                ],
            ],

            'meta' => [
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }
}
