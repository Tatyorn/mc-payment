<?php

namespace App\Exceptions\Errors;

use App\Exceptions\ApiErrorException;

class AuthenticationFailed extends ApiErrorException
{
    public function __construct()
    {
        parent::__construct(
            type: 'authentication_failed',
            title: 'Authentication Failed',
            status: 401,
            detail: 'Invalid credentials.',
        );
    }
}
