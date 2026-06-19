<?php

namespace App\Exceptions\Errors;

use App\Exceptions\ApiErrorException;

class ExchangeRateFailed extends ApiErrorException
{
    public function __construct()
    {
        parent::__construct(
            type: 'exchange_rate_failed',
            title: 'Exchange Rate Failed',
            status: 502,
            detail: __('errors.exchange_rate_failed'),
        );
    }
}
