<?php

namespace App\Exceptions\Errors;

use App\Exceptions\ApiErrorException;

class CurrencyNotSupported extends ApiErrorException
{
    public function __construct(string $currency)
    {
        parent::__construct(
            type: 'currency_not_supported',
            title: 'Currency Not Supported',
            status: 422,
            detail: __('errors.currency_not_supported', ['currency' => $currency]),
        );
    }
}
