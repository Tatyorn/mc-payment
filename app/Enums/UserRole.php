<?php

namespace App\Enums;

enum UserRole: string
{
    case EMPLOYEE = 'employee';
    case FINANCE = 'finance';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isFinance(): bool
    {
        return $this === self::FINANCE;
    }

    public function isEmployee(): bool
    {
        return $this === self::EMPLOYEE;
    }
}
