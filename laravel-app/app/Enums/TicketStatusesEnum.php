<?php

namespace App\Enums;

enum TicketStatusesEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public static function values(): array
    {
        return array_map(fn(self $s) => $s->value, self::cases());
    }
}
