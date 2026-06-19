<?php

namespace App\Exceptions\Errors;

use App\Exceptions\ApiErrorException;

class BusinessRuleViolation extends ApiErrorException
{
    public function __construct(string $detail)
    {
        parent::__construct(
            type: 'business_rule_violation',
            title: 'Business Rule Violation',
            status: 422,
            detail: $detail,
        );
    }
}
