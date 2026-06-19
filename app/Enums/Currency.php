<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case BRL = 'BRL';
    case GBP = 'GBP';
    case JPY = 'JPY';
    case CAD = 'CAD';
    case AUD = 'AUD';
    case CHF = 'CHF';
    case CNY = 'CNY';
    case ARS = 'ARS';
    case MXN = 'MXN';

    public function label(): string
    {
        $key = 'currency_'.$this->value;

        return __("enums.{$key}");
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
