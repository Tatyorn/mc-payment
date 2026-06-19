<?php

namespace App\Exceptions\Errors;

use App\Exceptions\ApiErrorException;

class NoActiveSession extends ApiErrorException
{
    public function __construct()
    {
        parent::__construct(
            type: 'no_active_session',
            title: 'No Active Session',
            status: 404,
            detail: __('errors.no_active_session'),
        );
    }
}
