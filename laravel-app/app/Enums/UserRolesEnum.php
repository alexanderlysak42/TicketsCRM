<?php

namespace App\Enums;

enum UserRolesEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';

    public static function values(): array
    {
        return array_map(fn(self $r) => $r->value, self::cases());
    }
}
