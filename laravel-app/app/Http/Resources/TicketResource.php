<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'status' => [
                'code' => $this->status->value ?? (string) $this->status,
                'label' => match ($this->status->value ?? $this->status) {
                    'new' => 'Новая',
                    'in_progress' => 'В работе',
                    'done' => 'Обработана',
                    default => 'Неизвестно',
                },
            ],

            'subject' => $this->subject,
            'message' => $this->message,

            'customer' => new CustomerResource($this->customer),

            'attachments' => $this->when(
                method_exists($this->resource, 'getMedia'),
                fn () => $this->getMedia('attachments')->map(fn ($media) => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'size' => $media->size,
                    'mime_type' => $media->mime_type,
                ])
            ),

            'answered_at' => $this->answered_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
