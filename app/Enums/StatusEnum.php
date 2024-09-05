<?php

namespace App\Enums;

enum StatusEnum: String
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case COMPLETE = 'COMPLETE';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
