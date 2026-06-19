<?php

namespace App\Enums;

enum PaymentRequestStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public function label(): string
    {
        $key = 'status_'.$this->value;

        return __("enums.{$key}");
    }
}
